#!/bin/bash

echo "=========================================="
echo "QR Attendance System - Installation Script"
echo "=========================================="

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP 8.2 or higher."
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo "✅ PHP Version: $PHP_VERSION"

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer."
    exit 1
fi

echo "✅ Composer is installed"

# Install dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

if [ $? -ne 0 ]; then
    echo "❌ Failed to install dependencies"
    exit 1
fi

echo "✅ Dependencies installed successfully"

# Set permissions
echo "🔐 Setting directory permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env

echo "✅ Permissions set successfully"

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

if [ $? -ne 0 ]; then
    echo "❌ Failed to generate application key"
    exit 1
fi

echo "✅ Application key generated"

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

if [ $? -ne 0 ]; then
    echo "❌ Failed to run migrations"
    exit 1
fi

echo "✅ Migrations completed"

# Create admin user
echo "👤 Creating admin user..."
php artisan tinker --execute="
    \$user = new App\Models\User();
    \$user->name = 'Super Admin';
    \$user->email = 'admin@qrattendance.com';
    \$user->password = Hash::make('password');
    \$user->email_verified_at = now();
    \$user->save();
    echo 'Admin user created with email: admin@qrattendance.com and password: password';
"

if [ $? -ne 0 ]; then
    echo "❌ Failed to create admin user"
    exit 1
fi

echo "✅ Admin user created"

# Mark as installed
echo "📝 Marking system as installed..."
echo "$(date)" > installed

echo "✅ Installation completed successfully!"
echo ""
echo "🎉 QR Attendance System is now installed!"
echo ""
echo "📋 Default Login Credentials:"
echo "   Email: admin@qrattendance.com"
echo "   Password: password"
echo ""
echo "🌐 You can now access the system at:"
echo "   http://your-domain.com"
echo ""
echo "🔒 For security, please:"
echo "   1. Change the default admin password"
echo "   2. Delete this install.sh file"
echo "   3. Set up proper SSL certificates"
echo ""
echo "=========================================="