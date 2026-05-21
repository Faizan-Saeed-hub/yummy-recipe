@echo off
title AI Recipe App Launcher
cd /d "%~dp0"
echo Launching all servers...
start "Laravel Server" cmd /c run_laravel.bat
start "Flask ML API" cmd /c run_ml_api.bat
echo All servers launched in separate windows!
echo - Laravel Web App: http://localhost:8000
echo - Flask ML API: http://localhost:5000
echo.
pause
