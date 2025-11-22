#!/bin/bash
# =====================================================
# Deployment Script - Update 22 Nov 2025
# PWS Computer Service Center
# =====================================================

echo "🚀 Starting deployment..."
echo ""

# =====================================================
# 1. BACKUP DATABASE
# =====================================================
echo "📦 Step 1: Backup Database..."
BACKUP_FILE="backup_before_update_$(date +%Y%m%d_%H%M%S).sql"
mysqldump -u root -p laravel > $BACKUP_FILE
echo "✅ Database backed up to: $BACKUP_FILE"
echo ""

# =====================================================
# 2. PULL LATEST CODE (Skip jika upload manual)
# =====================================================
echo "📥 Step 2: Pull Latest Code..."
read -p "Pakai Git pull? (y/n): " use_git
if [ "$use_git" = "y" ]; then
    git pull origin main
    echo "✅ Code updated from Git"
else
    echo "⏭️  Skipped - Upload manual via FTP"
fi
echo ""

# =====================================================
# 3. COMPOSER INSTALL (Skip jika tidak ada dependency baru)
# =====================================================
echo "📦 Step 3: Composer Install..."
read -p "Run composer install? (y/n): " run_composer
if [ "$run_composer" = "y" ]; then
    composer install --no-dev --optimize-autoloader
    echo "✅ Composer packages installed"
else
    echo "⏭️  Skipped"
fi
echo ""

# =====================================================
# 4. RUN MIGRATIONS
# =====================================================
echo "🗄️  Step 4: Run Migrations..."
php artisan migrate --force
echo ""

# =====================================================
# 5. CLEAR ALL CACHE
# =====================================================
echo "🧹 Step 5: Clear Cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
echo "✅ Cache cleared"
echo ""

# =====================================================
# 6. SET PERMISSIONS
# =====================================================
echo "🔐 Step 6: Set Permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
echo "✅ Permissions set"
echo ""

# =====================================================
# 7. RESTART SERVICES
# =====================================================
echo "🔄 Step 7: Restart Services..."
read -p "Restart PHP-FPM? (y/n): " restart_php
if [ "$restart_php" = "y" ]; then
    sudo systemctl restart php8.2-fpm
    echo "✅ PHP-FPM restarted"
else
    echo "⏭️  Skipped"
fi

read -p "Restart Queue Worker? (y/n): " restart_queue
if [ "$restart_queue" = "y" ]; then
    php artisan queue:restart
    echo "✅ Queue worker restarted"
else
    echo "⏭️  Skipped"
fi
echo ""

# =====================================================
# 8. VERIFY DEPLOYMENT
# =====================================================
echo "✅ Step 8: Verify Deployment..."
echo ""
echo "Checking migrations..."
php artisan migrate:status | grep "2025_11_22"
echo ""

echo "Checking routes..."
php artisan route:list | grep services | head -5
echo ""

echo "Checking Service model..."
php artisan tinker --execute="echo 'Services count: ' . \App\Models\Service::count();"
echo ""

# =====================================================
# DONE!
# =====================================================
echo "=========================================="
echo "✅ DEPLOYMENT COMPLETED!"
echo "=========================================="
echo ""
echo "📋 Next Steps:"
echo "1. Login ke admin panel"
echo "2. Test menu 'Master Jasa'"
echo "3. Test 'Analisa Selesai' dengan jasa & sparepart"
echo "4. Test Print Invoice"
echo "5. Test Edit pesanan + diskon"
echo ""
echo "📁 Backup Location: $BACKUP_FILE"
echo ""
echo "🆘 Jika ada masalah:"
echo "   - Restore: mysql -u root -p laravel < $BACKUP_FILE"
echo "   - Check logs: tail -f storage/logs/laravel.log"
echo ""
echo "Happy deploying! 🚀"
