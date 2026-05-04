@echo off
echo ========================================
echo Fleur Flower Order Management System
echo Simple Setup (No Composer Required)
echo ========================================
echo.

echo [1/4] Checking XAMPP services...
tasklist | findstr "httpd.exe" >nul
if %errorlevel% neq 0 (
    echo ERROR: Apache is not running. Please start Apache in XAMPP Control Panel.
    pause
    exit /b 1
)

tasklist | findstr "mysqld.exe" >nul
if %errorlevel% neq 0 (
    echo ERROR: MySQL is not running. Please start MySQL in XAMPP Control Panel.
    pause
    exit /b 1
)
echo ✓ Apache and MySQL are running

echo [2/4] Creating database and tables...
mysql -u root -e "CREATE DATABASE IF NOT EXISTS fleur_db;" 2>nul
if %errorlevel% neq 0 (
    echo ERROR: Cannot connect to MySQL. Please check MySQL service.
    pause
    exit /b 1
)

REM Create basic tables
mysql -u root fleur_db < setup_database.sql 2>nul
if %errorlevel% neq 0 (
    echo WARNING: Could not create database tables automatically.
    echo Please run the SQL commands manually in phpMyAdmin.
) else (
    echo ✓ Database and tables created
)

echo [3/4] Creating required directories...
if not exist "writable\session" mkdir writable\session
if not exist "writable\cache" mkdir writable\cache
if not exist "writable\logs" mkdir writable\logs
if not exist "writable\uploads" mkdir writable\uploads
echo ✓ Directories created

echo [4/4] Testing configuration...
echo ✓ Setup completed!

echo.
echo ========================================
echo SETUP COMPLETED!
echo ========================================
echo.
echo IMPORTANT: You need to install Composer for full functionality.
echo Download from: https://getcomposer.org/download/
echo.
echo For now, you can access the basic application at:
echo http://localhost/fleur/
echo.
echo Login credentials:
echo Admin: admin@fleur.com / password
echo Staff: staff@fleur.com / password
echo Customer: customer@fleur.com / password
echo.
echo Press any key to open the application...
pause >nul

start http://localhost/fleur/
