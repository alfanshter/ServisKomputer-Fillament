#!/bin/bash

echo "🚀 Starting deployment to server..."

# Pull latest code
echo "📥 Pulling latest code from git..."
git pull origin main

# Install/update dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Run only specific migration (discount)
echo "🔄 Running discount migration..."
php artisan migrate --path=database/migrations/2025_11_20_144457_add_discount_to_pesanans_table.php --force

# Clear cache
echo "🧹 Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimize
echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment completed!"
