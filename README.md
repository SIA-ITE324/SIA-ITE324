# Fleur - Flower Order Management System

A comprehensive flower order management system built with CodeIgniter 4, featuring order processing, inventory management, customer management, and reporting capabilities.

## Features

### Core Features
- **Order Processing**: Complete order lifecycle from placement to delivery tracking
- **Inventory Management**: Real-time stock monitoring with low stock alerts
- **Customer Management**: User profiles, order history, and communication tools
- **Payment System**: Cash on Delivery with automated billing
- **Shipping & Fulfillment**: Tracking integration and status notifications
- **Reporting & Analytics**: Sales, inventory, and performance insights
- **File Upload**: Excel/CSV support for bulk operations
- **Email Integration**: Brevo service for automated notifications

### User Roles
- **Admin**: Full system access and management capabilities
- **Staff**: Order processing and inventory management
- **Customer**: Order placement and tracking

## Technology Stack

- **Backend**: PHP 8.1+, CodeIgniter 4 Framework
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript, Tailwind CSS
- **Email**: Brevo API integration
- **File Processing**: PhpSpreadsheet for Excel/CSV handling

## Installation

### Prerequisites
- PHP 8.1 or higher
- MySQL/MariaDB database
- Web server (Apache/Nginx)
- Composer (for dependency management)

### Setup Instructions

1. **Clone/Download the Project**
   ```bash
   cd /xampp/htdocs/FLEUR
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Database Setup**
   - Create a database named `fleur_db`
   - Update database credentials in `app/Config/Database.php`
   - Run database migrations:
   ```bash
   php spark migrate
   ```

4. **Seed Initial Data**
   ```bash
   php spark db:seed DatabaseSeeder
   ```

5. **Environment Configuration**
   - Copy `env` to `.env`
   - Update base URL and other settings
   - Configure Brevo API keys for email functionality

6. **Web Server Configuration**
   - Point your web server to the `public` directory
   - Ensure `writable` directory has proper permissions

### Directory Structure

```
FLEUR/
├── app/
│   ├── Config/          # Configuration files
│   ├── Controllers/     # Application controllers
│   ├── Models/         # Database models
│   ├── Views/          # View templates
│   └── Database/       # Migrations and seeders
├── public/             # Public assets
├── writable/           # Writable files (uploads, logs, etc.)
├── vendor/             # Composer dependencies
└── system/             # CodeIgniter system files
```

## Configuration

### Database Settings
Update `app/Config/Database.php` with your database credentials:

```php
public array $default = [
    'hostname' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'database' => 'fleur_db',
    // ... other settings
];
```

### Email Configuration (Brevo)
Configure email settings in `.env`:

```env
email.fromEmail = noreply@fleur.com
email.fromName = Fleur Order System
email.SMTPHost = smtp-relay.brevo.com
email.SMTPUser = your-brevo-api-key
email.SMTPPass = your-brevo-smtp-key
brevo.apiKey = your-brevo-api-key
```

## Usage

### Default Login Credentials

**Admin Account:**
- Email: admin@fleur.com
- Password: admin123

**Staff Account:**
- Email: staff@fleur.com
- Password: staff123

**Customer Account:**
- Email: customer@fleur.com
- Password: customer123

### Admin Dashboard
Access the admin dashboard at `/admin/dashboard` after login.

### Main Features

#### Order Management
- Create, view, edit, and cancel orders
- Update order status and payment status
- Track orders with real-time status updates
- Print order invoices

#### Product Management
- Add and manage products
- Track inventory levels
- Set up product categories
- Bulk import/export products

#### Customer Management
- View customer profiles
- Track order history
- Manage customer communications

#### Reporting
- Sales analytics and reports
- Inventory reports
- Customer insights
- Export data to Excel/CSV

## API Endpoints

### Public API
- `GET /api/products` - List products
- `GET /api/products/{id}` - Get product details
- `GET /api/orders/status/{order_number}` - Get order status
- `POST /api/orders/track` - Track order

### Admin API
- Protected endpoints for admin operations
- Order and product management
- Reporting and analytics

## Security Features

- Role-based access control
- CSRF protection
- Input validation and sanitization
- Activity logging
- Secure password hashing
- Session management

## File Upload Support

- Excel (.xlsx, .xls) file import for products
- CSV file export for reports
- Image upload for products
- Secure file handling with validation

## Email Notifications

- Order status updates
- Payment confirmations
- Shipping notifications
- Customer registration confirmations
- Password reset emails

## Development

### Running Migrations
```bash
php spark migrate
```

### Running Seeders
```bash
php spark db:seed DatabaseSeeder
```

### Clearing Cache
```bash
php spark cache:clear
```

### Development Mode
Set `CI_ENVIRONMENT=development` in `.env` for debugging.

## Support

For support and documentation:
- Check the admin dashboard help section
- Review the code comments
- Contact development team

## License

This project is licensed under the MIT License.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## Version History

- **v1.0.0** - Initial release with core functionality
- Complete order management system
- Admin dashboard with reporting
- Customer portal
- Email integration
- File upload support
