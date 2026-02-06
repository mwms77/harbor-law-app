#!/bin/bash

echo "=================================="
echo "Estate Planning App - Setup Script"
echo "=================================="
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
    echo "âœ“ .env file created"
else
    echo "âœ“ .env file already exists"
fi

# Install composer dependencies
echo ""
echo "Installing Composer dependencies..."
composer install

# Install npm dependencies
echo ""
echo "Installing NPM dependencies..."
npm install

# Generate application key
echo ""
echo "Generating application key..."
php artisan key:generate

# Create storage directories
echo ""
echo "Creating storage directories..."
mkdir -p storage/app/private/intakes
mkdir -p storage/app/private/estate-plans
mkdir -p storage/app/public/logos
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# Set permissions
echo ""
echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache

echo ""
echo "=================================="
echo "Setup Complete!"
echo "=================================="
echo ""
echo "Next steps:"
echo "1. Edit .env file with your database credentials"
echo "2. Run: php artisan migrate"
echo "3. Run: php artisan db:seed"
echo "4. Run: php artisan storage:link"
echo "5. Run: npm run build"
echo "6. Change admin password after first login!"
echo ""
echo "Admin login credentials:"
echo "Email: admin@estate.local"
echo "Password: ChangeMe123!"
echo ""
