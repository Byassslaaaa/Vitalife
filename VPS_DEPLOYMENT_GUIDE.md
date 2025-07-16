# 🚀 VITALIFE VPS PRODUCTION DEPLOYMENT GUIDE

## 📋 Pre-Deployment Checklist

### 1. VPS Server Requirements
- [ ] PHP 8.2+ installed
- [ ] MySQL/MariaDB installed
- [ ] Nginx/Apache configured
- [ ] Composer installed
- [ ] Node.js & NPM installed
- [ ] SSL Certificate configured for vitalife.web.id
- [ ] Firewall configured (ports 80, 443, 22)

### 2. Domain & DNS Configuration
- [ ] vitalife.web.id points to VPS IP
- [ ] SSL certificate active
- [ ] Subdomain www.vitalife.web.id configured

### 3. Database Setup
```sql
-- Create database and user
CREATE DATABASE mainvita CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'ubay'@'localhost' IDENTIFIED BY '@Vitalife123';
GRANT ALL PRIVILEGES ON mainvita.* TO 'ubay'@'localhost';
FLUSH PRIVILEGES;
```

## 🔧 Deployment Steps

### Step 1: Clone Repository
```bash
cd /var/www/
git clone https://github.com/Byassslaaaa/Vitalife.git
cd Vitalife
```

### Step 2: Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### Step 3: Setup Environment
```bash
# Copy production environment file
cp .env.production .env

# Generate application key
php artisan key:generate

# Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Step 4: Database Migration
```bash
php artisan migrate --force
php artisan db:seed --force
```

### Step 5: Cache Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Step 6: Queue Setup
```bash
# Setup queue worker service
sudo systemctl enable vitalife-queue
sudo systemctl start vitalife-queue
```

## 📧 Email Configuration (Brevo)

### Current Production Settings
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=923371001@smtp-brevo.com
MAIL_PASSWORD=xsmtpsib-375b104b4a1ef4c1e132edc6ebcdf61e0c6cb819f9caed93f2a73f95b0a17033-RCUyA8NXz15aF6nM
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@vitalife.web.id
MAIL_FROM_NAME="Vitalife Admin"
```

### ⚠️ Important: Brevo Setup Requirements
1. **Verify Sender Address**: Add `admin@vitalife.web.id` as verified sender in Brevo dashboard
2. **Domain Verification**: Verify `vitalife.web.id` domain in Brevo
3. **API Key**: Ensure the SMTP credentials are active

### Email Testing Commands
```bash
# Test email system
php production_email_test.php

# Test via artisan tinker
php artisan tinker
>>> Mail::raw('Test', function($m) { $m->to('admin@vitalife.web.id')->subject('Test'); });
```

## 🔐 Security Configuration

### Environment Security
- [ ] APP_DEBUG=false in production
- [ ] APP_ENV=production
- [ ] Strong database passwords
- [ ] Secure session configuration
- [ ] HTTPS enforced

### File Permissions
```bash
# Set proper permissions
find /var/www/Vitalife -type f -exec chmod 644 {} \;
find /var/www/Vitalife -type d -exec chmod 755 {} \;
chmod -R 755 storage bootstrap/cache
chmod 644 .env
```

## 🌐 Web Server Configuration

### Nginx Configuration
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name vitalife.web.id www.vitalife.web.id;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name vitalife.web.id www.vitalife.web.id;
    root /var/www/Vitalife/public;

    index index.php;

    charset utf-8;

    # SSL Configuration
    ssl_certificate /path/to/ssl/cert.pem;
    ssl_certificate_key /path/to/ssl/private.key;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 📊 Monitoring & Maintenance

### Log Monitoring
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check web server logs
tail -f /var/log/nginx/error.log
tail -f /var/log/nginx/access.log
```

### Health Checks
```bash
# Check services
systemctl status nginx
systemctl status mysql
systemctl status php8.2-fpm
systemctl status vitalife-queue

# Check disk space
df -h

# Check memory usage
free -h
```

### Backup Strategy
```bash
# Database backup
mysqldump -u ubay -p@Vitalife123 mainvita > backup_$(date +%Y%m%d).sql

# File backup
tar -czf vitalife_backup_$(date +%Y%m%d).tar.gz /var/www/Vitalife
```

## 🔧 Troubleshooting

### Common Issues

#### 1. Email Not Sending
**Symptoms**: Registration/booking emails not received
**Solutions**:
- Check Brevo dashboard for sending status
- Verify `admin@vitalife.web.id` is added as verified sender
- Check Laravel logs: `tail -f storage/logs/laravel.log`
- Test SMTP connection manually

#### 2. 500 Internal Server Error
**Solutions**:
- Check `storage/logs/laravel.log`
- Verify file permissions
- Clear all caches: `php artisan optimize:clear`
- Check web server error logs

#### 3. Queue Jobs Not Processing
**Solutions**:
- Check queue worker status: `systemctl status vitalife-queue`
- Restart queue worker: `php artisan queue:restart`
- Check failed jobs: `php artisan queue:failed`

#### 4. Social Login Issues
**Solutions**:
- Update OAuth redirect URLs to production domain
- Verify SSL certificate is working
- Check Google/Facebook app settings

### Emergency Contacts
- **VPS Provider**: [Your VPS provider support]
- **Domain Registrar**: [Your domain provider]
- **Brevo Support**: support@brevo.com

## 📈 Performance Optimization

### Recommended Settings
```bash
# Enable OPcache
echo "opcache.enable=1" >> /etc/php/8.2/fpm/php.ini

# Increase memory limits
echo "memory_limit=512M" >> /etc/php/8.2/fpm/php.ini

# Configure Redis for sessions (optional)
# REDIS_HOST=127.0.0.1
# SESSION_DRIVER=redis
```

## 🎯 Post-Deployment Testing

### Test Checklist
- [ ] Website loads correctly (https://vitalife.web.id)
- [ ] User registration works and sends email
- [ ] Booking system functional
- [ ] Payment system working
- [ ] Social login (Google/Facebook) working
- [ ] Admin panel accessible
- [ ] Email notifications working
- [ ] SSL certificate valid

### Performance Testing
```bash
# Test website speed
curl -o /dev/null -s -w "Total time: %{time_total}s\n" https://vitalife.web.id

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

---
**Last Updated**: July 16, 2025
**Version**: Production v1.0
**Environment**: VPS Production
