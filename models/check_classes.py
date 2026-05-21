import torch, json
from pathlib import Path

MODEL_DIR = Path('models')
ckpt = torch.load(MODEL_DIR / 'best_fusion_model.pth', map_location='cpu', weights_only=False)

model_classes = ckpt.get('classes', [])
with open(MODEL_DIR / 'classes.json') as f:
    json_classes = json.load(f)

print(f'Model checkpoint classes: {len(model_classes)}')
print(f'classes.json classes:     {len(json_classes)}')
print()

model_set = set(model_classes)
json_set  = set(json_classes)

only_in_model = model_set - json_set
only_in_json  = json_set - model_set

print(f'In model but NOT in classes.json: {sorted(only_in_model)}')
print(f'In classes.json but NOT in model: {sorted(only_in_json)}')
print()

print('--- Side by side comparison ---')
max_len = max(len(model_classes), len(json_classes))
for i in range(max_len):
    mc = model_classes[i] if i < len(model_classes) else 'N/A'
    jc = json_classes[i] if i < len(json_classes) else 'N/A'
    flag = '  ' if mc == jc else '!!'
    print(f'{flag} [{i:3}] model={mc:35} json={jc}')
