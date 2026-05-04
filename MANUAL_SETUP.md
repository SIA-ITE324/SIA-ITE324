# Manual Setup Guide for Fleur Flower Order Management System

## Step 1: Verify XAMPP is Running

1. Open XAMPP Control Panel
2. Make sure both **Apache** and **MySQL** services are running (green background)
3. If not running, click the "Start" button for each service

## Step 2: Create Database

1. Open your web browser
2. Go to: `http://localhost/phpmyadmin`
3. Click on **"New"** in the left sidebar
4. Enter database name: `fleur_db`
5. Click **"Create"**
6. Select the `fleur_db` database from the left sidebar

## Step 3: Create Database Tables

1. With the `fleur_db` database selected, click on the **"SQL"** tab
2. Copy and paste the following SQL code (or open the `setup_database.sql` file):

```sql
-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','customer') NOT NULL DEFAULT 'customer',
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default users (password: "password")
INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
('Admin', 'User', 'admin@fleur.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', NOW(), NOW()),
('Staff', 'User', 'staff@fleur.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff', 'active', NOW(), NOW()),
('Customer', 'User', 'customer@fleur.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'active', NOW(), NOW());

-- Create categories table
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(3) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample categories
INSERT INTO `categories` (`name`, `slug`, `description`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
('Roses', 'roses', 'Beautiful roses in various colors and arrangements', 1, 'active', NOW(), NOW()),
('Lilies', 'lilies', 'Elegant lilies for any occasion', 2, 'active', NOW(), NOW()),
('Tulips', 'tulips', 'Colorful tulips perfect for spring', 3, 'active', NOW(), NOW());

-- Create products table
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(5) unsigned DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `sku` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(5) NOT NULL DEFAULT 0,
  `min_stock_level` int(5) NOT NULL DEFAULT 5,
  `status` enum('active','inactive','out_of_stock') NOT NULL DEFAULT 'active',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample products
INSERT INTO `products` (`category_id`, `name`, `slug`, `description`, `short_description`, `sku`, `price`, `stock_quantity`, `min_stock_level`, `status`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 'Red Rose Bouquet', 'red-rose-bouquet', 'A beautiful bouquet of fresh red roses, perfect for expressing love and romance.', '12 premium red roses with greenery', 'RB-001', 49.99, 50, 10, 'active', 1, NOW(), NOW()),
(2, 'Pink Lily Bouquet', 'pink-lily-bouquet', 'Delicate pink lilies that bring beauty and fragrance to any room.', '10 pink lilies with filler flowers', 'PL-003', 39.99, 40, 10, 'active', 0, NOW(), NOW()),
(3, 'Colorful Tulip Mix', 'colorful-tulip-mix', 'A vibrant mix of colorful tulips that brighten any day.', '20 mixed color tulips', 'TM-004', 34.99, 60, 15, 'active', 1, NOW(), NOW());

-- Create orders table
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `customer_id` int(5) unsigned NOT NULL,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled','refunded') NOT NULL DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_method` enum('cod','bank_transfer','credit_card','paypal') NOT NULL DEFAULT 'cod',
  `subtotal` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `shipping_address` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create order_items table
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(5) unsigned NOT NULL,
  `product_id` int(5) unsigned NOT NULL,
  `quantity` int(5) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_sku` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

3. Click **"Go"** or **"Execute"** to run the SQL commands
4. You should see "Query executed successfully" messages

## Step 4: Create Required Folders

1. Open File Explorer
2. Navigate to: `C:\xampp\htdocs\FLEUR`
3. Create the following folders if they don't exist:
   - `writable\session`
   - `writable\cache`
   - `writable\logs`
   - `writable\uploads`

## Step 5: Configure Application

1. Open the `env` file in Notepad or any text editor
2. Update these settings:

```env
app.baseURL = 'http://localhost/fleur/'
app.indexPage = 'index.php'

database.default.hostname = localhost
database.default.database = fleur_db
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port = 3306
```

3. Save the file

## Step 6: Test the Application

1. Open your web browser
2. Go to: `http://localhost/fleur/`
3. You should see the Fleur homepage

## Step 7: Login to Admin Dashboard

1. Click on **"Login"** or go to `http://localhost/fleur/login`
2. Use admin credentials:
   - **Email**: `admin@fleur.com`
   - **Password**: `password`
3. After login, you should be redirected to the admin dashboard

## Troubleshooting

### If you get "404 Not Found":
- Make sure Apache is running in XAMPP
- Try accessing: `http://localhost/fleur/index.php`
- Check that you're in the correct directory

### If you get "Database Connection Failed":
- Verify MySQL is running in XAMPP
- Check that database `fleur_db` exists in phpMyAdmin
- Verify database credentials in `env` file

### If you get "Permission Denied":
- Make sure the `writable` folder and subfolders exist
- Try running your browser as Administrator

### If you get "White Screen":
- Check XAMPP error logs: `C:\xampp\apache\logs\error.log`
- Enable debug mode by adding to `.env`: `CI_ENVIRONMENT = development`

## Next Steps

Once you can access the application:

1. **Explore the Admin Dashboard**
   - Navigate to different sections
   - Test the order management features

2. **Add More Products**
   - Go to Products section
   - Add your own flower products

3. **Test Order Creation**
   - Create a test order to verify functionality

## Success!

If you can successfully login to the admin dashboard, your Fleur Flower Order Management System is ready to use!

**Quick Access Links:**
- Homepage: `http://localhost/fleur/`
- Login: `http://localhost/fleur/login`
- Admin Dashboard: `http://localhost/fleur/admin/dashboard`

**Login Credentials:**
- Admin: `admin@fleur.com` / `password`
- Staff: `staff@fleur.com` / `password`
- Customer: `customer@fleur.com` / `password`
