@echo off
title Twiis Innovations Local Development Server
echo =======================================================
echo     Twiis Innovations Local Development Server
echo =======================================================
echo.

:: Ensure we are in the script's directory
cd /d "%~dp0"

:: Detect PHP from XAMPP or Path
set PHP_EXE=C:\xampp\php\php.exe
if not exist "%PHP_EXE%" (
    where php >nul 2>nul
    if %ERRORLEVEL% equ 0 (
        set PHP_EXE=php
    ) else (
        echo [ERROR] PHP executable not found at C:\xampp\php\php.exe or in system PATH.
        echo Please ensure XAMPP is installed or PHP is added to your environment variables.
        echo.
        pause
        exit /b 1
    )
)

echo [1/3] Using PHP path: %PHP_EXE%
echo.

:: Initialize Database
echo [2/3] Initializing SQLite database...
cd api
"%PHP_EXE%" init-db.php
cd ..
echo.

:: Start PHP server with router
echo [3/3] Starting built-in PHP server at http://localhost:8000 ...
echo [INFO] Press Ctrl+C in this window to stop the server.
echo.

:: Open default browser
start http://localhost:8000

:: Run the server
"%PHP_EXE%" -S localhost:8000 router.php

pause
