# QR Attendance System - Installation Guide

## Overview

The QR Attendance System is a Laravel-based application that allows tracking student attendance using QR codes. This guide provides multiple installation methods to suit different deployment scenarios.

## System Requirements

### Server Requirements
- **PHP**: 8.2 or higher
- **Database**: MySQL 5.7+ or MariaDB 10.2+
- **Web Server**: Apache/Nginx
- **Composer**: Latest version

### PHP Extensions
- BCMath
- Ctype
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- Fileinfo
- GD
- Curl
- Zip
- SQLite3 (optional)

### Directory Permissions
- `storage/app` - Writable
- `storage/framework` - Writable
- `storage/logs` - Writable
- `bootstrap/cache` - Writable
- `.env` file - Writable

## Installation Methods

### Method 1: Web-Based Installation (Recommended)

1. **Upload Files**
   ```bash
   # Upload all files to your web server
   # Ensure the web server can access the files
   ```

2. **Set Permissions**
   ```bash
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   chmod 644 .env
   ```

3. **Access Installation**
   - Open your browser and navigate to: `http://your-domain.com/install`
   - Follow the step-by-step installation wizard

4. **Installation Steps**
   - **Step 1**: System Requirements Check
   - **Step 2**: Database Configuration
   - **Step 3**: Administrator Setup
   - **Step 4**: Installation Complete

### Method 2: Command Line Installation

1. **Run the Installation Script**
   ```bash
   ./install.sh
   ```

2. **Manual Installation**
   ```bash
   # Install dependencies
   composer install --no-dev --optimize-autoloader
   
   # Generate application key
   php artisan key:generate
   
   # Run migrations
   php artisan migrate --force
   
   # Create admin user
   php artisan tinker --execute="
       \$user = new App\Models\User();
       \$user->name = 'Super Admin';
       \$user->email = 'admin@qrattendance.com';
       \$user->password = Hash::make('password');
       \$user->email_verified_at = now();
       \$user->save();
   "
   
   # Mark as installed
   echo "$(date)" > installed
   ```

### Method 3: Docker Installation

1. **Create Docker Compose File**
   ```yaml
   version: '3.8'
   services:
     app:
       build: .
       ports:
         - "8000:8000"
       environment:
         - DB_HOST=db
         - DB_DATABASE=qrattendance
         - DB_USERNAME=root
         - DB_PASSWORD=password
       depends_on:
         - db
     
     db:
       image: mysql:8.0
       environment:
         - MYSQL_DATABASE=qrattendance
         - MYSQL_ROOT_PASSWORD=password
       volumes:
         - db_data:/var/lib/mysql
   
   volumes:
     db_data:
   ```

2. **Run Installation**
   ```bash
   docker-compose up -d
   docker-compose exec app ./install.sh
   ```

## Database Configuration

### MySQL/MariaDB
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=qrattendance
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### SQLite (Development)
```env
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite
```

## Post-Installation

### 1. Security Setup
- Change default admin password
- Set up SSL certificates
- Configure firewall rules
- Delete installation files

### 2. Email Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="QR Attendance System"
```

### 3. Queue Configuration (Optional)
```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## Default Credentials

After installation, you can log in with:
- **Email**: admin@qrattendance.com
- **Password**: password

**⚠️ Important**: Change these credentials immediately after installation!

## Troubleshooting

### Common Issues

1. **Permission Denied**
   ```bash
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   ```

2. **Database Connection Failed**
   - Verify database credentials
   - Ensure database server is running
   - Check firewall settings

3. **Composer Dependencies**
   ```bash
   composer install --no-dev
   composer dump-autoload
   ```

4. **Cache Issues**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

### Error Logs
- Check Laravel logs: `storage/logs/laravel.log`
- Check web server logs
- Enable debug mode in `.env`: `APP_DEBUG=true`

## File Structure

```
qr-attendance-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Install/
│   │   │       └── InstallController.php
│   │   └── Middleware/
│   │       └── CheckInstallation.php
│   └── Models/
├── install/
│   └── InstallController.php
├── resources/
│   └── views/
│       └── install/
│           ├── index.blade.php
│           └── requirements.blade.php
├── routes/
│   ├── web.php
│   └── install.php
├── install.sh
├── INSTALLATION.md
└── README.md
```

## Support

For installation support:
1. Check the troubleshooting section above
2. Review Laravel documentation
3. Check system requirements
4. Verify server configuration

## Security Notes

- Always use HTTPS in production
- Regularly update dependencies
- Monitor access logs
- Use strong passwords
- Keep backups of your database
- Delete installation files after setup

## License

This project is licensed under the MIT License.

# Easy Installation (Shared Hosting)

This project is optimized for shared hosting where Composer/CLI access may be limited.

## Option A: Quick Install (SQLite, production-ready)

1. Upload all project files to your hosting account (document root or a subfolder)
2. Ensure the following directories are writable:
   - `storage/`
   - `bootstrap/cache/`
3. Visit `https://your-domain.com/install/quick`
   - The app will:
     - Create `.env` (if missing) with `APP_ENV=production`, `APP_DEBUG=false`, `DB_CONNECTION=sqlite`
     - Create `database/database.sqlite`
     - Generate `APP_KEY`
     - Run migrations and seeders
     - Mark installation complete
4. Login with Super Admin (if seeded) or register and then promote via database if needed.

Notes:
- If your hosting points to the repository root, the provided root `.htaccess` will redirect requests to `public/` automatically.
- If your hosting allows pointing the document root to `public/`, you can remove the root `.htaccess`.

## Option B: Manual Install (MySQL)

1. Upload files and set permissions (`storage`, `bootstrap/cache` writable)
2. Create a MySQL database and user
3. Copy `.env.example` to `.env` and set `DB_CONNECTION=mysql` and DB credentials
4. If CLI is available:
   - `php artisan key:generate`
   - `php artisan migrate --force`
   - `php artisan db:seed --force`
   - Otherwise, use the browser installer:
     - Visit `/install`
     - Go through requirements and configuration, then click Install

## Production Tips
- Set `APP_URL` in `.env`
- Ensure `APP_ENV=production` and `APP_DEBUG=false`
- Set correct file permissions per your host
- Configure mail `MAIL_*` for password resets/notifications
- In Admin → Settings, configure WhatsApp provider and credentials if you plan to use notifications