@echo off
echo ========================================
echo Fleur Flower Order Management System
echo Quick Setup Script
echo ========================================
echo.

echo Checking prerequisites...
echo.

REM Check if XAMPP is running
echo [1/6] Checking XAMPP services...
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

REM Check Composer
echo [2/6] Checking Composer...
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Composer is not installed or not in PATH.
    echo Please install Composer from https://getcomposer.org/
    pause
    exit /b 1
)
echo ✓ Composer is available

REM Check if database exists
echo [3/6] Checking database...
mysql -u root -e "USE fleur_db;" 2>nul
if %errorlevel% neq 0 (
    echo Database 'fleur_db' not found. Creating database...
    mysql -u root -e "CREATE DATABASE fleur_db;"
    echo ✓ Database created
) else (
    echo ✓ Database exists
)

REM Install dependencies
echo [4/6] Installing PHP dependencies...
composer install --no-dev --optimize-autoloader
if %errorlevel% neq 0 (
    echo ERROR: Failed to install dependencies.
    pause
    exit /b 1
)
echo ✓ Dependencies installed

REM Set permissions
echo [5/6] Setting file permissions...
if not exist "writable\session" mkdir writable\session
if not exist "writable\cache" mkdir writable\cache
if not exist "writable\logs" mkdir writable\logs
if not exist "writable\uploads" mkdir writable\uploads
echo ✓ Directories created

REM Test configuration
echo [6/6] Testing configuration...
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: PHP is not available in command line.
    pause
    exit /b 1
)
echo ✓ PHP is working

echo.
echo ========================================
echo SETUP COMPLETED SUCCESSFULLY!
echo ========================================
echo.
echo Next steps:
echo 1. Open your web browser
echo 2. Navigate to: http://localhost/fleur/
echo 3. Login with admin@fleur.com / password
echo.
echo For detailed setup instructions, see SETUP.md
echo.
echo Press any key to open the application in your browser...
pause >nul

start http://localhost/fleur/

echo Setup complete! The application should now be opening in your browser.
