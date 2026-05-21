@echo off
title Flask ML API
cd /d "%~dp0"
echo Starting Flask ML API Server on http://localhost:5000 ...
.venv\Scripts\python.exe models/api.py
pause
