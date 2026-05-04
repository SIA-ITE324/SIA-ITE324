# Fleur - Flower Order Management System Setup Guide

This guide will help you set up the Fleur Flower Order Management System on your local development environment.

## Prerequisites

Before you begin, ensure you have the following installed:

### Required Software
- **XAMPP** (includes Apache, MySQL, PHP) - Download from https://www.apachefriends.org/
- **PHP 8.1+** (included with XAMPP)
- **MySQL/MariaDB** (included with XAMPP)
- **Composer** (PHP package manager) - Download from https://getcomposer.org/

### Optional (for development)
- **VS Code** with PHP extensions
- **Git** for version control

## Step-by-Step Setup

### Step 1: Verify XAMPP Installation

1. Open XAMPP Control Panel
2. Start Apache and MySQL services
3. Test Apache by visiting `http://localhost` in your browser
4. Test MySQL by accessing phpMyAdmin at `http://localhost/phpmyadmin`

### Step 2: Install Composer (if not already installed)

1. Download Composer from https://getcomposer.org/Composer-Setup.exe
2. Run the installer and follow the prompts
3. Open Command Prompt and verify installation:
   ```cmd
   composer --version
   ```

### Step 3: Set Up the Database

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click on "New" to create a new database
3. Enter database name: `fleur_db`
4. Click "Create"
5. Select the database and click on the "SQL" tab
6. Run the following SQL to create the database structure:

```sql
-- Create users table
CREATE TABLE `users` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','staff','customer') NOT NULL DEFAULT 'customer',
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `email_verification_token` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `password_reset_expires` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user
INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
('Admin', 'User', 'admin@fleur.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', NOW(), NOW()),
('Staff', 'User', 'staff@fleur.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff', 'active', NOW(), NOW()),
('Customer', 'User', 'customer@fleur.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'active', NOW(), NOW());

-- Create categories table
CREATE TABLE `categories` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `parent_id` int(5) unsigned DEFAULT NULL,
  `sort_order` int(3) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample categories
INSERT INTO `categories` (`name`, `slug`, `description`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
('Roses', 'roses', 'Beautiful roses in various colors and arrangements', 1, 'active', NOW(), NOW()),
('Lilies', 'lilies', 'Elegant lilies for any occasion', 2, 'active', NOW(), NOW()),
('Tulips', 'tulips', 'Colorful tulips perfect for spring', 3, 'active', NOW(), NOW()),
('Orchids', 'orchids', 'Exotic orchids for special occasions', 4, 'active', NOW(), NOW()),
('Mixed Bouquets', 'mixed-bouquets', 'Beautiful mixed flower arrangements', 5, 'active', NOW(), NOW()),
('Floral Arrangements', 'floral-arrangements', 'Professional floral arrangements for events', 6, 'active', NOW(), NOW());

-- Create products table
CREATE TABLE `products` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(5) unsigned DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `sku` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(5) NOT NULL DEFAULT 0,
  `min_stock_level` int(5) NOT NULL DEFAULT 5,
  `weight` decimal(8,2) DEFAULT NULL,
  `dimensions_length` decimal(8,2) DEFAULT NULL,
  `dimensions_width` decimal(8,2) DEFAULT NULL,
  `dimensions_height` decimal(8,2) DEFAULT NULL,
  `images` text DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `status` enum('active','inactive','out_of_stock') NOT NULL DEFAULT 'active',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_digital` tinyint(1) NOT NULL DEFAULT 0,
  `track_stock` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `sku` (`sku`),
  KEY `category_id` (`category_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create orders table
CREATE TABLE `orders` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `customer_id` int(5) unsigned NOT NULL,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled','refunded') NOT NULL DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_method` enum('cod','bank_transfer','credit_card','paypal') NOT NULL DEFAULT 'cod',
  `subtotal` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `shipping_address` text NOT NULL,
  `billing_address` text DEFAULT NULL,
  `customer_notes` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `shipping_method` varchar(100) DEFAULT NULL,
  `estimated_delivery` date DEFAULT NULL,
  `actual_delivery` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `customer_id` (`customer_id`),
  KEY `status` (`status`),
  KEY `payment_status` (`payment_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create order_items table
CREATE TABLE `order_items` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(5) unsigned NOT NULL,
  `product_id` int(5) unsigned NOT NULL,
  `quantity` int(5) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_sku` varchar(100) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_options` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create inventory table
CREATE TABLE `inventory` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(5) unsigned NOT NULL,
  `quantity_before` int(5) NOT NULL,
  `quantity_after` int(5) NOT NULL,
  `quantity_change` int(5) NOT NULL,
  `type` enum('sale','purchase','adjustment','return','damage','transfer') NOT NULL,
  `reference_id` int(5) unsigned DEFAULT NULL,
  `reference_type` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(5) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `type` (`type`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create activity_logs table
CREATE TABLE `activity_logs` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(5) unsigned DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50) NOT NULL,
  `entity_id` int(5) unsigned DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `action` (`action`),
  KEY `entity_type` (`entity_type`),
  KEY `entity_id` (`entity_id`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Step 4: Configure Application Settings

1. Open the `env` file in the project root
2. Update the following settings:

```env
# APP
app.baseURL = 'http://localhost/fleur/'
app.indexPage = 'index.php'
app.appTimezone = 'UTC'

# DATABASE
database.default.hostname = localhost
database.default.database = fleur_db
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = 3306

# SESSION
session.driver = file
session.cookieName = ci_session
session.expiration = 7200
session.savePath = WRITEPATH . 'session'
```

### Step 5: Set Up Virtual Host (Optional but Recommended)

1. Open `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
2. Add the following virtual host configuration:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/FLEUR/public"
    ServerName fleur.local
    ServerAlias www.fleur.local
    
    <Directory "C:/xampp/htdocs/FLEUR/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "logs/fleur-error.log"
    CustomLog "logs/fleur-access.log" common
</VirtualHost>
```

3. Open `C:\Windows\System32\drivers\etc\hosts`
4. Add this line at the end:
   ```
   127.0.0.1 fleur.local
   ```

### Step 6: Set File Permissions

1. Navigate to the project directory in Command Prompt
2. Set write permissions for writable directories:

```cmd
icacls writable /grant Everyone:(OI)(CI)F
```

### Step 7: Test the Application

1. Open your web browser
2. Navigate to one of these URLs:
   - `http://localhost/fleur/` (if using direct access)
   - `http://fleur.local/` (if using virtual host)

### Step 8: Login to the System

Use these default credentials to test the system:

**Admin Login:**
- URL: `http://localhost/fleur/login`
- Email: `admin@fleur.com`
- Password: `password`

**Staff Login:**
- Email: `staff@fleur.com`
- Password: `password`

**Customer Login:**
- Email: `customer@fleur.com`
- Password: `password`

## Troubleshooting

### Common Issues

1. **"404 Not Found" Error**
   - Check if Apache is running
   - Verify the DocumentRoot path in virtual host configuration
   - Ensure mod_rewrite is enabled in Apache

2. **"Database Connection Failed"**
   - Verify MySQL is running in XAMPP
   - Check database credentials in `env` file
   - Ensure database `fleur_db` exists

3. **"Permission Denied" Errors**
   - Set proper permissions on `writable` directory
   - Run Command Prompt as Administrator

4. **"Composer Not Found"**
   - Restart Command Prompt after installing Composer
   - Add Composer to system PATH

5. **"White Screen" or "500 Error"**
   - Check PHP error logs in `C:\xampp\apache\logs\error.log`
   - Enable error reporting in PHP for debugging

### Debug Mode

To enable debug mode, add this to your `.env` file:
```env
CI_ENVIRONMENT = development
```

## Next Steps

After successful setup:

1. **Explore the Admin Dashboard**
   - Navigate to `/admin/dashboard`
   - Review the system statistics
   - Test the order management features

2. **Add Sample Data**
   - Create sample products
   - Add test categories
   - Create sample orders

3. **Configure Email Settings**
   - Set up Brevo API credentials
   - Test email notifications

4. **Customize the System**
   - Modify the design as needed
   - Add custom features
   - Configure business-specific settings

## Support

If you encounter any issues during setup:

1. Check the error logs in `C:\xampp\apache\logs\`
2. Review the PHP error log in `writable/logs/`
3. Ensure all prerequisites are properly installed
4. Verify database connection and permissions

## Security Notes

For production deployment:

1. Change default passwords immediately
2. Use HTTPS with SSL certificates
3. Set proper file permissions
4. Disable error reporting in production
5. Use environment-specific database credentials
6. Regularly update dependencies
