@echo off
title Laravel Server
cd /d "%~dp0"
echo Starting Laravel Web Server on http://localhost:8000 ...
c:\xampp\php\php.exe artisan serve
pause
