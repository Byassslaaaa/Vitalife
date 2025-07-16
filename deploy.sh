#!/bin/bash

# Vitalife VPS Deployment Script
# Run this script on your VPS to deploy the application

set -e

echo "🚀 VITALIFE VPS DEPLOYMENT SCRIPT"
echo "================================="

# Configuration
PROJECT_NAME="Vitalife"
PROJECT_PATH="/var/www/Vitalife"
WEB_USER="www-data"
PHP_VERSION="8.2"

echo "📋 Starting deployment process..."

# Step 1: Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo "❌ Please run this script as root or with sudo"
    exit 1
fi

# Step 2: Update system packages
echo "📦 Updating system packages..."
apt update && apt upgrade -y

# Step 3: Install required packages
echo "🔧 Installing required packages..."
apt install -y nginx mysql-server php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-gd php8.2-curl php8.2-mbstring php8.2-zip php8.2-bcmath php8.2-intl composer nodejs npm git

# Step 4: Clone or update repository
if [ -d "$PROJECT_PATH" ]; then
    echo "📁 Updating existing repository..."
    cd $PROJECT_PATH
    git pull origin main
else
    echo "📁 Cloning repository..."
    cd /var/www/
    git clone https://github.com/Byassslaaaa/Vitalife.git
    cd $PROJECT_PATH
fi

# Step 5: Install dependencies
echo "📦 Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev

echo "📦 Installing Node.js dependencies..."
npm install

echo "🏗️  Building assets..."
npm run build

# Step 6: Setup environment
echo "⚙️  Setting up environment..."
if [ ! -f ".env" ]; then
    if [ -f ".env.production" ]; then
        cp .env.production .env
        echo "✅ Copied .env.production to .env"
    else
        echo "❌ No .env.production file found!"
        exit 1
    fi
fi

# Generate app key if not exists
php artisan key:generate --force

# Step 7: Set permissions
echo "🔐 Setting file permissions..."
chown -R $WEB_USER:$WEB_USER $PROJECT_PATH
chmod -R 755 $PROJECT_PATH/storage $PROJECT_PATH/bootstrap/cache
chmod 644 $PROJECT_PATH/.env

# Step 8: Database setup
echo "🗄️  Setting up database..."
read -p "Have you created the database 'mainvita' and user 'ubay'? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "📊 Running migrations..."
    cd $PROJECT_PATH
    php artisan migrate --force

    read -p "Do you want to run database seeders? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        php artisan db:seed --force
    fi
else
    echo "⚠️  Please create the database and user manually:"
    echo "   CREATE DATABASE mainvita CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    echo "   CREATE USER 'ubay'@'localhost' IDENTIFIED BY '@Vitalife123';"
    echo "   GRANT ALL PRIVILEGES ON mainvita.* TO 'ubay'@'localhost';"
    echo "   FLUSH PRIVILEGES;"
fi

# Step 9: Optimize for production
echo "⚡ Optimizing for production..."
cd $PROJECT_PATH
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Step 10: Setup Nginx configuration
echo "🌐 Setting up Nginx configuration..."
cat > /etc/nginx/sites-available/vitalife << EOF
server {
    listen 80;
    listen [::]:80;
    server_name vitalife.web.id www.vitalife.web.id;
    return 301 https://\$server_name\$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name vitalife.web.id www.vitalife.web.id;
    root $PROJECT_PATH/public;

    index index.php;
    charset utf-8;

    # SSL Configuration (update paths as needed)
    ssl_certificate /etc/ssl/certs/vitalife.web.id.crt;
    ssl_certificate_key /etc/ssl/private/vitalife.web.id.key;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php$PHP_VERSION-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Enable the site
ln -sf /etc/nginx/sites-available/vitalife /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
nginx -t

if [ $? -eq 0 ]; then
    echo "✅ Nginx configuration is valid"
    systemctl reload nginx
else
    echo "❌ Nginx configuration error!"
    exit 1
fi

# Step 11: Setup queue worker service
echo "🔄 Setting up queue worker..."
cat > /etc/systemd/system/vitalife-queue.service << EOF
[Unit]
Description=Vitalife Queue Worker
After=network.target

[Service]
Type=simple
User=$WEB_USER
WorkingDirectory=$PROJECT_PATH
ExecStart=/usr/bin/php $PROJECT_PATH/artisan queue:work --sleep=3 --tries=3
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
EOF

systemctl enable vitalife-queue
systemctl start vitalife-queue

# Step 12: Setup cron job for Laravel scheduler
echo "⏰ Setting up cron job..."
(crontab -u $WEB_USER -l 2>/dev/null; echo "* * * * * cd $PROJECT_PATH && php artisan schedule:run >> /dev/null 2>&1") | crontab -u $WEB_USER -

# Step 13: Test email configuration
echo "📧 Testing email configuration..."
cd $PROJECT_PATH
php production_email_test.php

# Step 14: Final status check
echo "🔍 Final status check..."
systemctl status nginx --no-pager -l
systemctl status php$PHP_VERSION-fpm --no-pager -l
systemctl status mysql --no-pager -l
systemctl status vitalife-queue --no-pager -l

echo ""
echo "🎉 DEPLOYMENT COMPLETED!"
echo "======================="
echo "✅ Website: https://vitalife.web.id"
echo "✅ Admin Panel: https://vitalife.web.id/admin"
echo "✅ Queue Worker: Running"
echo "✅ Email System: Configured"
echo ""
echo "📋 Next Steps:"
echo "1. Configure SSL certificate if not done yet"
echo "2. Test all functionality thoroughly"
echo "3. Update OAuth redirect URLs for production"
echo "4. Setup monitoring and backups"
echo ""
echo "📞 If you encounter issues, check:"
echo "- Laravel logs: tail -f $PROJECT_PATH/storage/logs/laravel.log"
echo "- Nginx logs: tail -f /var/log/nginx/error.log"
echo "- Queue status: systemctl status vitalife-queue"
