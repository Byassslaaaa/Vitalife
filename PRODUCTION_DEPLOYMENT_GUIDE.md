# 🚀 VITALIFE PRODUCTION DEPLOYMENT GUIDE
## Setup Email Notifications dengan Brevo di VPS

### ✅ KONFIGURASI YANG SUDAH DIUPDATE

#### 1. Environment Configuration (.env)
```bash
# Production Settings
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://vitalife.web.id

# Brevo SMTP Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=923371001@smtp-brevo.com
MAIL_PASSWORD=xsmtpsib-375b104b4a1ef4c1e132edc6ebcdf61e0c6cb819f9caed93f2a73f95b0a17033-RCUyA8NXz15aF6nM
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@vitalife.web.id
MAIL_FROM_NAME="Vitalife Admin"

# Production OAuth
GOOGLE_REDIRECT_URI=https://vitalife.web.id/auth/google/callback

# Production Payment
MIDTRANS_IS_PRODUCTION=true
```

#### 2. Email Features yang Sudah Terintegrasi
- ✅ **Welcome Email** - Otomatis saat registrasi user baru
- ✅ **Booking Confirmation** - Otomatis saat booking spa/gym/yoga berhasil
- ✅ **Payment Success** - Otomatis saat payment via Midtrans berhasil
- ✅ **Admin Notifications** - Notifikasi ke admin saat ada user baru

### 🔧 DEPLOYMENT STEPS DI VPS

#### Step 1: Upload Project ke VPS
```bash
# Upload semua file project ke /var/www/vitalife/
# Pastikan permissions benar
sudo chown -R www-data:www-data /var/www/vitalife/
sudo chmod -R 755 /var/www/vitalife/
sudo chmod -R 775 /var/www/vitalife/storage/
sudo chmod -R 775 /var/www/vitalife/bootstrap/cache/
```

#### Step 2: Install Dependencies
```bash
cd /var/www/vitalife/
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

#### Step 3: Setup Database
```bash
# Update .env dengan database production
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vitalife_production
DB_USERNAME=vitalife_user
DB_PASSWORD=secure_password

# Run migrations
php artisan migrate --force
php artisan db:seed --force
```

#### Step 4: Configure Laravel untuk Production
```bash
# Generate application key (jika belum ada)
php artisan key:generate

# Cache konfigurasi untuk performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Setup storage link
php artisan storage:link

# Setup queue worker (untuk email processing)
php artisan queue:table
php artisan migrate
```

#### Step 5: Setup Queue Worker untuk Email
```bash
# Install supervisor untuk queue worker
sudo apt install supervisor

# Buat config supervisor
sudo nano /etc/supervisor/conf.d/vitalife-worker.conf
```

**Content file supervisor:**
```ini
[program:vitalife-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/vitalife/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/vitalife/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Start supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start vitalife-worker:*
```

### 🌐 NGINX CONFIGURATION

#### /etc/nginx/sites-available/vitalife.web.id
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
    
    root /var/www/vitalife/public;
    index index.php index.html index.htm;
    
    # SSL Configuration (use Certbot/Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/vitalife.web.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/vitalife.web.id/privkey.pem;
    
    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
    
    # Optimize static files
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### 📧 EMAIL TESTING & TROUBLESHOOTING

#### Test Email dari VPS
```bash
# Masuk ke VPS dan test email
cd /var/www/vitalife/
php artisan tinker

# Di dalam tinker:
$emailService = new App\Services\EmailNotificationService();
$testUser = (object) ['id' => 1, 'name' => 'Test User', 'email' => 'your-email@gmail.com', 'phone' => '+6281234567890'];
$result = $emailService->sendWelcomeEmail($testUser);
echo $result ? 'SUCCESS' : 'FAILED';
exit
```

#### Monitoring Email Queue
```bash
# Cek queue status
php artisan queue:work --once

# Monitor queue jobs
php artisan queue:monitor

# Cek failed jobs
php artisan queue:failed

# Restart queue worker
sudo supervisorctl restart vitalife-worker:*
```

#### Check Logs
```bash
# Laravel logs
tail -f /var/www/vitalife/storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/error.log

# Queue worker logs
tail -f /var/www/vitalife/storage/logs/worker.log
```

### 🔍 COMMON ISSUES & SOLUTIONS

#### 1. Email Tidak Terkirim
- ✅ Pastikan credentials Brevo benar di .env
- ✅ Cek queue worker running: `sudo supervisorctl status`
- ✅ Cek logs: `tail -f storage/logs/laravel.log`
- ✅ Test manual: `php artisan tinker`

#### 2. 500 Internal Server Error
- ✅ Cek permissions: `sudo chown -R www-data:www-data /var/www/vitalife/`
- ✅ Clear cache: `php artisan config:clear && php artisan cache:clear`
- ✅ Check .env file exists dan readable

#### 3. Database Connection Error
- ✅ Pastikan MySQL running: `sudo systemctl status mysql`
- ✅ Test database connection: `mysql -u username -p database_name`
- ✅ Cek .env database settings

#### 4. SSL/HTTPS Issues
- ✅ Install SSL: `sudo certbot --nginx -d vitalife.web.id`
- ✅ Auto-renewal: `sudo crontab -e` tambah `0 12 * * * /usr/bin/certbot renew --quiet`

### 📊 MONITORING & MAINTENANCE

#### Daily Checks
```bash
# Check application status
curl -I https://vitalife.web.id

# Check queue worker
sudo supervisorctl status vitalife-worker:*

# Check disk space
df -h

# Check logs for errors
tail -20 /var/www/vitalife/storage/logs/laravel.log
```

#### Weekly Maintenance
```bash
# Update dependencies
composer update
npm update && npm run build

# Clear old logs
find /var/www/vitalife/storage/logs/ -name "*.log" -mtime +30 -delete

# Optimize database
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 🎯 PRODUCTION READY CHECKLIST

- ✅ Environment set to `production`
- ✅ Debug mode disabled (`APP_DEBUG=false`)
- ✅ Secure database credentials
- ✅ Brevo SMTP configured with production credentials
- ✅ SSL certificate installed
- ✅ Queue worker configured with Supervisor
- ✅ File permissions set correctly
- ✅ Error pages customized (optional)
- ✅ Backup strategy implemented
- ✅ Monitoring setup (logs, uptime)

### 📞 SUPPORT & CONTACT

Jika ada issues setelah deployment:
1. Check logs terlebih dahulu
2. Test email functionality manual
3. Verify all environment variables
4. Restart services: nginx, php-fpm, mysql, supervisor

**Deployment berhasil berarti:**
- ✅ Website accessible via https://vitalife.web.id
- ✅ Email notifications working for registration & booking
- ✅ Payment system integrated with Midtrans
- ✅ Admin panel accessible dan functional
