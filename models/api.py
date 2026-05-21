import os
import sys

lib_path = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'venv', 'Lib', 'site-packages', 'torch', 'lib')
if os.path.exists(lib_path) and hasattr(os, 'add_dll_directory'):
    os.add_dll_directory(lib_path)

os.environ["OPENBLAS_NUM_THREADS"] = "1"
os.environ["OMP_NUM_THREADS"] = "1"
os.environ["MKL_NUM_THREADS"] = "1"

import numpy as np
import json
import torch
import torch.nn as nn
from torchvision import transforms
from PIL import Image
from collections import Counter
import timm
from flask import Flask, request, jsonify
from pathlib import Path
from meal_plan_engine import generate_meal_plan

app = Flask(__name__)

# Enable CORS for Laravel communication
from flask_cors import CORS
CORS(app)

# Config
DEVICE = "cuda" if torch.cuda.is_available() else "cpu"
IMG_SIZE = 300
MODEL_DIR = Path(__file__).parent
META_PATH = MODEL_DIR / "dish_metadata (1).json"
CLASSES_PATH = MODEL_DIR / "classes (1).json"
MODEL_PATH = MODEL_DIR / "connection.pth"

# Load Data
with open(META_PATH, 'r') as f:
    META = json.load(f)

# Load classes from checkpoint first (most accurate — matches training order)
# Fall back to classes.json only if checkpoint doesn't have classes key
print("Loading model checkpoint to extract classes...")
_ckpt_preview = torch.load(MODEL_PATH, map_location='cpu', weights_only=False)
if "classes" in _ckpt_preview:
    CLASSES = _ckpt_preview["classes"]
    print(f"Loaded {len(CLASSES)} classes from checkpoint (training order).")
else:
    with open(CLASSES_PATH, 'r') as f:
        CLASSES = json.load(f)
    print(f"Loaded {len(CLASSES)} classes from classes.json (fallback).")
print("First 5 classes:", CLASSES[:5])

NUM_CLASSES = len(CLASSES)

def build_vocab(meta):
    counter = Counter()
    for v in meta.values():
        for ing in v.get("ingredients", []):
            counter[ing.lower()] += 1
    vocab = {k:i for i,(k,_) in enumerate(counter.most_common(500))}
    return vocab

VOCAB = build_vocab(META)
VOCAB_SIZE = len(VOCAB)

class FusionModel(nn.Module):
    def __init__(self):
        super().__init__()
        self.cnn = timm.create_model("efficientnet_b3", pretrained=False, num_classes=0)
        self.meta = nn.Sequential(
            nn.Linear(VOCAB_SIZE + 2, 256),
            nn.ReLU(),
            nn.BatchNorm1d(256)
        )
        self.head = nn.Linear(self.cnn.num_features + 256, NUM_CLASSES)

    def forward(self, x, m):
        f_img = self.cnn(x)
        f_meta = self.meta(m)
        f = torch.cat([f_img, f_meta], dim=1)
        return self.head(f)

# Load Model (reuse already-loaded checkpoint)
model = FusionModel().to(DEVICE)
try:
    ckpt = _ckpt_preview
    if "model_state_dict" in ckpt:
        model.load_state_dict(ckpt["model_state_dict"])
    else:
        model.load_state_dict(ckpt)
    model.eval()
    print("Model loaded successfully.")
except Exception as e:
    print(f"Error loading model: {e}")

val_tfms = transforms.Compose([
    transforms.Resize(320),
    transforms.CenterCrop(IMG_SIZE),
    transforms.ToTensor(),
    transforms.Normalize([0.485,0.456,0.406],[0.229,0.224,0.225])
])

@app.route('/status', methods=['GET'])
def status():
    """Health check endpoint for the ML API"""
    return jsonify({
        "success": True,
        "status": "Model running",
        "device": DEVICE,
        "model": "EfficientNet B3 + Fusion",
        "num_classes": NUM_CLASSES,
        "image_size": IMG_SIZE
    })

@app.route('/predict', methods=['POST'])
def predict():
    if 'image' not in request.files:
        return jsonify({"error": "No image provided"}), 400

    file = request.files['image']
    if file.filename == '':
        return jsonify({"error": "No image selected"}), 400

    try:
        # Preprocess Image
        img = Image.open(file.stream).convert('RGB')
        img_tensor = val_tfms(img).unsqueeze(0).to(DEVICE)

        # Create dummy metadata vector
        m = torch.zeros(1, VOCAB_SIZE + 2).to(DEVICE)

        # Inference
        with torch.no_grad():
            out = model(img_tensor, m)
            probs = torch.softmax(out, dim=1)[0]  # confidence scores

            # Top-3 predictions
            top3_values, top3_indices = torch.topk(probs, 3)
            top3 = []
            for score, idx in zip(top3_values.tolist(), top3_indices.tolist()):
                pred_class = CLASSES[idx]
                dish_meta = META.get(pred_class, {})
                top3.append({
                    "prediction": pred_class,
                    "confidence": round(score * 100, 2),
                    "metadata": dish_meta
                })

        # Best prediction is top1
        best = top3[0]

        return jsonify({
            "success": True,
            "prediction": best["prediction"],
            "confidence": best["confidence"],
            "metadata": best["metadata"],
            "top3": top3
        })
    except Exception as e:
        return jsonify({"error": str(e)}), 500


@app.route('/meal-plan', methods=['POST'])
def meal_plan():
    """
    Generate a professional AI meal plan.
    Expects JSON body with user health + goal data.
    """
    try:
        data = request.get_json(force=True)

        # Required fields
        goal           = data.get('goal', 'loss')           # 'loss' or 'gain'
        weight_kg      = float(data.get('weight_kg', 70))
        target_kg      = float(data.get('target_weight_kg', 65))
        height_cm      = float(data.get('height_cm', 170))
        age            = int(data.get('age', 25))
        gender         = data.get('gender', 'male')
        activity_level = data.get('activity_level', 'moderately_active')
        duration_days  = int(data.get('duration_days', 7))

        # Optional profile fields (from user session)
        health_conditions    = data.get('health_conditions', [])    # list of strings
        dietary_restrictions = data.get('dietary_restrictions', []) # list of strings

        if weight_kg <= 0 or height_cm <= 0 or age <= 0:
            return jsonify({"success": False, "error": "Invalid weight, height, or age."}), 400

        result = generate_meal_plan(
            goal=goal,
            weight_kg=weight_kg,
            target_weight_kg=target_kg,
            height_cm=height_cm,
            age=age,
            gender=gender,
            activity_level=activity_level,
            health_conditions=health_conditions,
            dietary_restrictions=dietary_restrictions,
            duration_days=duration_days,
        )
        return jsonify(result)

    except Exception as e:
        return jsonify({"success": False, "error": str(e)}), 500


if __name__ == '__main__':
    port = int(os.environ.get('PORT', 5000))
    app.run(host='0.0.0.0', port=port, debug=False)




# http://localhost:8080/phpmyadmin/
# cd c:\xampp\htdocs\RECEPIE
# & .venv\Scripts\python.exe .\RECEPIE\models\api.py


# cd c:\xampp\htdocs\RECEPIE\RECEPIE
# & "c:\xampp\php\php.exe" artisan serve --host=localhost --port=8000