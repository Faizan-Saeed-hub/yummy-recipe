# 🍳 Recipe Website - Setup & Launch Guide

## Project Overview
This is a **Laravel 10 web application** with an **integrated Python ML model** for food/recipe recognition using deep learning.

### Components:
- **Backend**: Laravel 10 (PHP)
- **Frontend**: Blade templates + Vite.js
- **Database**: MySQL
- **ML Model**: PyTorch-based EfficientNet B3 for image recognition
- **ML API**: Flask server for model inference

---

## ✅ Installation Complete!

All dependencies have been successfully installed:
- ✅ PHP Composer packages (Laravel framework, dependencies)
- ✅ Python ML environment (PyTorch, Flask, TimM, etc.)
- ⏳ Node.js/npm (needs to be installed separately - see below)

---

## 🚀 How to Run the Application

### **IMPORTANT: Open 3 separate terminals**

---

### **Terminal 1: Laravel Web Server**
```bash
cd c:\xampp\htdocs\RECEPIE\RECEPIE
c:\xampp\php\php.exe artisan serve
```
This will start the Laravel development server on `http://localhost:8000`

---

### **Terminal 2: Flask ML API Server**
```bash
cd c:\xampp\htdocs\RECEPIE\RECEPIE\models
python api.py
```
This will start the PyTorch ML model API on `http://localhost:5000`

The API supports:
- `POST /predict` - Send image + ingredients for food recognition
- `GET /status` - Check if ML model is running

---

### **Terminal 3: Vite Development Server (Optional but Recommended)**
First, install Node.js from: https://nodejs.org/

Then run:
```bash
cd c:\xampp\htdocs\RECEPIE\RECEPIE
npm install
npm run dev
```
This enables hot-reload for frontend changes on `http://localhost:5173`

---

## 📊 Database Configuration

The application uses **MySQL** with the following settings:
- **Database Name**: `yummy_recipes`
- **Host**: 127.0.0.1
- **Port**: 3306
- **User**: root
- **Password**: (empty)

Make sure XAMPP MySQL service is running!

**Check via XAMPP Control Panel** or run:
```bash
# Windows XAMPP:
# Open XAMPP Control Panel and click "Start" for MySQL
```

To verify database connection:
```bash
c:\xampp\php\php.exe artisan migrate:status
```

---

## 🔌 API Integration

The Flask ML model is integrated with Laravel through the **PredictionController**.

### Making predictions from Laravel:

```php
// POST /api/predict
// Send image + ingredients to ML model
```

### Check model status:
```bash
# GET /api/model/status
# Returns: Device (CPU/GPU), Model info, Num classes, etc.
```

---

## 📁 Project Structure

```
RECEPIE/
├── app/                          # Laravel application code
│   ├── Http/Controllers/
│   │   ├── PredictionController.php    # ✨ NEW: ML API integration
│   │   ├── Admin/                      # Admin controllers
│   │   └── Customer/                   # Customer controllers
│   ├── Models/                   # Eloquent models
│   │   ├── User.php
│   │   ├── Category.php
│   │   └── Message.php
│   └── Providers/                # Service providers
├── models/                       # Python ML model
│   ├── api.py                    # ✨ NEW: Flask API with CORS
│   ├── connection.pth            # Pre-trained model weights
│   ├── dish_metadata (1).json    # Recipe metadata
│   ├── classes (1).json          # Food classes
│   └── requirements.txt          # Python dependencies
├── database/
│   ├── migrations/               # Database schema
│   └── seeders/                  # Database seeders
├── routes/
│   ├── web.php                   # ✨ NEW: Added /api/predict routes
│   └── api.php
├── resources/
│   ├── views/                    # Blade templates
│   └── css/js/                   # Frontend assets
├── public/                       # Web root
│   └── index.php
├── composer.json                 # PHP dependencies
├── package.json                  # Node.js dependencies
└── .env                          # Environment configuration
```

---

## ⚙️ Environment Setup

Edit `.env` file if needed:

```env
APP_NAME=Recipe_App
APP_ENV=local
APP_KEY=base64:gbokPYBGEU42yjFKhTkPi7cdniFdH8j9Au6unClvg5o=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yummy_recipes
DB_USERNAME=root
DB_PASSWORD=

# Python ML API
FLASK_API_URL=http://localhost:5000
```

---

## 🔧 Troubleshooting

### Issue: "composer command not found"
**Solution**: Use the installed composer binary:
```bash
cd c:\xampp\htdocs\RECEPIE\RECEPIE
c:\xampp\php\php.exe composer install
```

### Issue: "npm command not found"
**Solution**: Install Node.js from https://nodejs.org/ then:
```bash
npm install
npm run dev
```

### Issue: MySQL won't connect
**Solution**: 
1. Start XAMPP Control Panel
2. Click "Start" for MySQL service
3. Check `.env` database credentials

### Issue: Flask API won't start
**Solution**:
```bash
cd c:\xampp\htdocs\RECEPIE\RECEPIE\models
python api.py
# If error, check model files exist:
# - connection.pth
# - dish_metadata (1).json
# - classes (1).json
```

### Issue: Model not loading in Flask
**Solution**: Verify PyTorch installation:
```bash
python -c "import torch; print(torch.__version__); print(torch.cuda.is_available())"
```

---

## 🌐 Access Points

Once all servers are running:

| Service | URL | Purpose |
|---------|-----|---------|
| **Laravel** | `http://localhost:8000` | Main web application |
| **Vite HMR** | `http://localhost:5173` | Frontend hot reload (if running) |
| **Flask API** | `http://localhost:5000` | ML model prediction API |
| **Status Check** | `http://localhost:5000/status` | Check if ML model is running |

---

## 📋 Key Features

✨ **Food Recognition**: Upload image → AI recognizes dish → Shows metadata
🥘 **Recipe Search**: Search by ingredients
📊 **Nutritional Info**: View nutritional details
🍽️ **Meal Planner**: Plan meals
💬 **Messaging**: Contact form

---

## 🛠️ Make Changes

### Add new Recipe:
Edit database migrations in `database/migrations/`

### Customize ML Model:
Edit `models/api.py`

### Add new Web Pages:
1. Create Controller: `app/Http/Controllers/`
2. Add Route: `routes/web.php`
3. Create View: `resources/views/`

### Update Frontend:
- Edit `resources/js/` and `resources/css/`
- Changes reload automatically via Vite

---

## 📞 Support

- **PHP/Laravel docs**: https://laravel.com/docs
- **PyTorch docs**: https://pytorch.org/docs
- **Flask docs**: https://flask.palletsprojects.com/

---

**Ready to go! Start with Terminal 1 (Laravel), then Terminal 2 (Flask) 🚀**
