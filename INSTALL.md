# Quick Installation Guide

## 🚀 5-Minute Quick Setup

### Step 1: Start XAMPP Services
1. Open XAMPP Control Panel
2. Start **Apache** and **MySQL** services
3. Verify both services are running (green background)

### Step 2: Create Database
1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click **"New"** in the left sidebar
3. Enter database name: `fleur_db`
4. Click **"Create"**

### Step 3: Run Setup Script
1. Open Command Prompt (as Administrator)
2. Navigate to your project folder:
   ```cmd
   cd C:\xampp\htdocs\FLEUR
   ```
3. Run the setup script:
   ```cmd
   setup.bat
   ```

### Step 4: Access the Application
1. Open your browser
2. Go to: `http://localhost/fleur/`
3. Login with:
   - **Email**: `admin@fleur.com`
   - **Password**: `password`

## 📋 Manual Setup (If Setup Script Fails)

### 1. Database Setup
Open `http://localhost/phpmyadmin` and run this SQL:

```sql
CREATE DATABASE fleur_db;
USE fleur_db;

CREATE TABLE users (
    id int(5) unsigned NOT NULL AUTO_INCREMENT,
    first_name varchar(100) NOT NULL,
    last_name varchar(100) NOT NULL,
    email varchar(100) NOT NULL,
    password varchar(255) NOT NULL,
    role enum('admin','staff','customer') NOT NULL DEFAULT 'customer',
    status enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
    created_at datetime NOT NULL,
    updated_at datetime NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
);

INSERT INTO users (first_name, last_name, email, password, role, status, created_at, updated_at) VALUES
('Admin', 'User', 'admin@fleur.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', NOW(), NOW());
```

### 2. Install Dependencies
In Command Prompt:
```cmd
cd C:\xampp\htdocs\FLEUR
composer install
```

### 3. Create Folders
Create these folders if they don't exist:
- `writable/session`
- `writable/cache`
- `writable/logs`
- `writable/uploads`

### 4. Test the Application
Go to: `http://localhost/fleur/`

## 🔧 Configuration

### Update Database Settings (if needed)
Edit the `env` file:
```env
database.default.database = fleur_db
database.default.username = root
database.default.password = 
```

### Change Base URL (if needed)
In `env` file:
```env
app.baseURL = 'http://localhost/fleur/'
```

## 🎯 First Things to Do After Setup

1. **Login to Admin Dashboard**
   - Go to `http://localhost/fleur/admin/dashboard`
   - Use admin credentials

2. **Add Categories**
   - Navigate to Products → Categories
   - Add flower categories (Roses, Lilies, etc.)

3. **Add Products**
   - Navigate to Products → Add Product
   - Add sample flower products

4. **Test Order Creation**
   - Create a test order to verify the system works

## 🆘 Troubleshooting

### "404 Not Found"
- Make sure Apache is running in XAMPP
- Check if you're accessing `http://localhost/fleur/` (not the root folder)

### "Database Connection Failed"
- Verify MySQL is running in XAMPP
- Check database name in `env` file
- Ensure database `fleur_db` exists

### "Permission Denied"
- Run Command Prompt as Administrator
- Check folder permissions in `writable` directory

### "White Screen"
- Check XAMPP error logs: `C:\xampp\apache\logs\error.log`
- Enable error reporting by adding to `.env`: `CI_ENVIRONMENT = development`

## 📞 Support

If you still have issues:

1. Check the detailed `SETUP.md` file
2. Verify all steps in the manual setup
3. Check XAMPP error logs
4. Ensure all prerequisites are installed

## 🎉 You're Ready!

Once you can access `http://localhost/fleur/` and login with the admin credentials, your Fleur Flower Order Management System is ready to use!

**Default Login Credentials:**
- **Admin**: admin@fleur.com / password
- **Staff**: staff@fleur.com / password  
- **Customer**: customer@fleur.com / password
