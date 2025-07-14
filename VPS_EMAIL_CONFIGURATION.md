# 📧 Konfigurasi Email Server VPS untuk Vitalife

## 🎯 Setting Email VPS Production

Berdasarkan konfigurasi VPS Anda dengan domain `vitalife.web.id`, berikut adalah dokumentasi lengkap untuk setup email server.

## ⚙️ Konfigurasi .env untuk VPS Production

### 📝 Setting Email Variables di .env

Ganti konfigurasi email di file `.env` VPS Anda dengan:

```env
# ============================================================================
# EMAIL CONFIGURATION - VPS PRODUCTION
# ============================================================================

MAIL_MAILER=smtp
MAIL_HOST=mail.vitalife.web.id
MAIL_PORT=587
MAIL_USERNAME=admin@vitalife.web.id
MAIL_PASSWORD=password_email_admin
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@vitalife.web.id
MAIL_FROM_NAME="Vitalife"
```

### 🔧 Setting Tambahan yang Perlu Diperhatikan

#### 1. **App Configuration** (Pastikan sesuai dengan VPS)
```env
APP_NAME=Vitalife
APP_ENV=production
APP_DEBUG=false
APP_URL=https://vitalife.web.id
```

#### 2. **Queue Configuration** (Untuk performa email yang lebih baik)
```env
QUEUE_CONNECTION=database
# atau
QUEUE_CONNECTION=redis
```

#### 3. **Session & Cache** (Production optimization)
```env
SESSION_DRIVER=database
CACHE_STORE=database
```

## 🛠️ Setup Email Server di VPS

### 📋 Checklist Setup Email Server

#### **1. DNS Records Configuration**
Pastikan DNS records sudah dikonfigurasi di domain provider:

```dns
# MX Record
vitalife.web.id.    IN    MX    10    mail.vitalife.web.id.

# A Record untuk mail server
mail.vitalife.web.id.    IN    A    [IP_VPS_ANDA]

# SPF Record (untuk menghindari spam)
vitalife.web.id.    IN    TXT    "v=spf1 include:mail.vitalife.web.id ~all"

# DKIM Record (jika menggunakan DKIM)
default._domainkey.vitalife.web.id.    IN    TXT    "v=DKIM1; k=rsa; p=[PUBLIC_KEY]"

# DMARC Record (untuk security)
_dmarc.vitalife.web.id.    IN    TXT    "v=DMARC1; p=quarantine; rua=mailto:dmarc@vitalife.web.id"
```

#### **2. Email Accounts yang Perlu Dibuat**

Buat email accounts berikut di cPanel/control panel VPS:

```
# Admin Email (untuk sistem)
admin@vitalife.web.id - password_email_admin

# Support Email (untuk customer service)
support@vitalife.web.id - [password_support]

# No-Reply Email (untuk notifikasi otomatis)
noreply@vitalife.web.id - [password_noreply]

# Backup Email (optional)
backup@vitalife.web.id - [password_backup]
```

#### **3. SSL/TLS Certificate**
Pastikan SSL certificate sudah terinstall untuk:
- Domain utama: `vitalife.web.id`
- Mail subdomain: `mail.vitalife.web.id`

## 🎯 Quick Testing Guide

### **🌐 Akses Email Testing Interface**

1. **Buka browser dan akses:**
   ```
   https://vitalife.web.id/email-test
   ```

2. **Langkah-langkah testing:**
   - ✅ Masukkan email tujuan (contoh: `admin@vitalife.web.id`)
   - ✅ Pilih jenis email yang ingin ditest (Welcome, Spa, Yoga, Gym)
   - ✅ Klik "🚀 Kirim Email Test"
   - ✅ Lihat response dan check email inbox

3. **Verifikasi hasil:**
   - Email masuk ke inbox ✅
   - Design template tampil dengan baik ✅
   - Data booking sesuai dengan jenis layanan ✅
   - Tidak masuk spam folder ✅

### **📋 Checklist Testing per Email Type**

#### **🎉 Welcome Email Test**
- [ ] Subject: "🎉 Selamat Datang di Vitalife..."
- [ ] Greeting dengan nama user
- [ ] Info layanan (Gym, Spa, Yoga)
- [ ] Bonus discount 20% dengan kode WELCOME20
- [ ] Contact information lengkap

#### **💆‍♀️ Spa Booking Email Test**
- [ ] Subject: "💆‍♀️ Konfirmasi Booking Spa..."
- [ ] Booking code dengan format SPA-VPS-xxxxx
- [ ] Info treatment: durasi, therapist preference
- [ ] Tips persiapan spa
- [ ] Fasilitas dan kebijakan spa

#### **🧘‍♀️ Yoga Booking Email Test**
- [ ] Subject: "🧘‍♀️ Booking Yoga Berhasil!"
- [ ] Booking code dengan format YOGA-VPS-xxxxx
- [ ] Info kelas: jenis, participants
- [ ] Tips persiapan yoga
- [ ] Apa yang perlu dibawa

#### **💪 Gym Booking Email Test**
- [ ] Subject: "💪 Booking Gym Berhasil!"
- [ ] Booking code dengan format GYM-VPS-xxxxx
- [ ] Info workout: durasi session
- [ ] Tips dan fasilitas gym
- [ ] Program fitness recommendations

## 🧪 Testing Email Configuration

### **1. Web Interface Testing (Recommended)**

Akses halaman testing email melalui browser:
```
https://vitalife.web.id/email-test
```

Interface ini menyediakan:
- ✅ Form testing yang user-friendly
- ✅ Display konfigurasi SMTP saat ini
- ✅ Testing untuk semua jenis email (Welcome, Spa, Yoga, Gym)
- ✅ Real-time response dan error handling
- ✅ Visual feedback untuk status pengiriman

### **2. Test via Laravel Command**

```bash
# Login ke VPS dan masuk ke direktori project
cd /path/to/vitalife/project

# Clear cache configuration
php artisan config:clear
php artisan cache:clear

# Test email functionality
php artisan email:test welcome admin@vitalife.web.id
php artisan email:test yoga admin@vitalife.web.id
php artisan email:test spa admin@vitalife.web.id
php artisan email:test gym admin@vitalife.web.id
```

### **3. Test via Tinker**

```bash
php artisan tinker
```

```php
// Test basic email
Mail::raw('Test email from VPS', function($message) {
    $message->to('admin@vitalife.web.id')
            ->subject('VPS Email Test');
});

// Test Welcome Email
$userData = [
    'userName' => 'VPS Test User',
    'userEmail' => 'admin@vitalife.web.id',
    'userPhone' => '+62 812-3456-7890',
    'supportEmail' => 'support@vitalife.web.id'
];
Mail::to('admin@vitalife.web.id')->send(new App\Mail\WelcomeEmail($userData));
```

### **4. Test Script untuk VPS**

Buat file `vps_email_test.php` di root project:

```php
<?php
// VPS Email Test Script
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Testing VPS Email Configuration...\n";
echo "📧 SMTP Host: " . env('MAIL_HOST') . "\n";
echo "👤 From: " . env('MAIL_FROM_ADDRESS') . "\n\n";

try {
    $testData = [
        'userName' => 'VPS Admin',
        'userEmail' => 'admin@vitalife.web.id',
        'userPhone' => '+62 812-3456-7890',
        'supportEmail' => 'support@vitalife.web.id'
    ];

    Mail::to('admin@vitalife.web.id')->send(new App\Mail\WelcomeEmail($testData));
    echo "✅ VPS Email test berhasil!\n";
    echo "📬 Check inbox: admin@vitalife.web.id\n";
} catch (Exception $e) {
    echo "❌ VPS Email test gagal: " . $e->getMessage() . "\n";
}
```

Jalankan dengan: `php vps_email_test.php`

## 🔒 Security Configuration

### **1. Firewall Rules**
Pastikan port SMTP terbuka di firewall VPS:

```bash
# Untuk Ubuntu/Debian
sudo ufw allow 587/tcp  # SMTP Submission
sudo ufw allow 465/tcp  # SMTPS (optional)
sudo ufw allow 25/tcp   # SMTP (optional, untuk incoming)

# Untuk CentOS/RHEL
sudo firewall-cmd --permanent --add-port=587/tcp
sudo firewall-cmd --permanent --add-port=465/tcp
sudo firewall-cmd --reload
```

### **2. Email Security Headers**

Update mail configuration untuk security headers:

```php
// config/mail.php - Tambah security headers
'from' => [
    'address' => env('MAIL_FROM_ADDRESS', 'admin@vitalife.web.id'),
    'name' => env('MAIL_FROM_NAME', 'Vitalife'),
],

// Tambah custom headers untuk security
'stream' => [
    'ssl' => [
        'verify_peer' => true,
        'verify_peer_name' => true,
        'allow_self_signed' => false,
    ],
],
```

## 📊 Monitoring & Logging

### **1. Email Logs**

Monitor email logs di VPS:

```bash
# Laravel logs
tail -f storage/logs/laravel.log | grep -i mail

# Mail server logs (Postfix)
tail -f /var/log/mail.log

# cPanel mail logs
tail -f /var/log/exim_mainlog
```

### **2. Email Queue Monitoring**

Jika menggunakan queue untuk email:

```bash
# Start queue worker
php artisan queue:work --daemon

# Monitor queue
php artisan queue:failed
php artisan queue:retry all
```

## 🚀 Production Deployment Checklist

### **✅ Pre-Deployment**
- [ ] DNS records dikonfigurasi
- [ ] SSL certificate terinstall
- [ ] Email accounts dibuat
- [ ] SMTP credentials tested
- [ ] Firewall rules configured

### **✅ Deployment**
- [ ] .env file updated dengan setting VPS
- [ ] `php artisan config:cache`
- [ ] `php artisan cache:clear`
- [ ] Test email functionality
- [ ] Monitor email delivery

### **✅ Post-Deployment**
- [ ] Email deliverability test
- [ ] Spam score check
- [ ] Monitoring setup
- [ ] Backup configuration

## 🔧 Troubleshooting Common Issues

### **Problem: SMTP Connection Timeout**
```bash
# Check SMTP connectivity
telnet mail.vitalife.web.id 587

# Check DNS resolution
nslookup mail.vitalife.web.id

# Check firewall
sudo netstat -tulnp | grep :587
```

### **Problem: Authentication Failed**
- Verify email account password
- Check if email account exists
- Ensure username format is correct

### **Problem: Emails Going to Spam**
- Configure SPF, DKIM, DMARC records
- Check sender reputation
- Use proper email content structure

## 📈 Performance Optimization

### **1. Queue Configuration**
```env
# Untuk high-volume email
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Queue workers
QUEUE_WORKER_TIMEOUT=300
QUEUE_RETRY_AFTER=300
```

### **2. Email Rate Limiting**
```php
// Implement rate limiting untuk email
RateLimiter::for('emails', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});
```

## 📞 Support & Maintenance

### **Email Accounts untuk Support**
- `admin@vitalife.web.id` - Sistem dan notifikasi
- `support@vitalife.web.id` - Customer service
- `noreply@vitalife.web.id` - Notifikasi otomatis

### **Regular Maintenance**
- Monitor email delivery rates
- Check email logs weekly
- Update SSL certificates annually
- Review and update spam filters

---

## 🎯 Final Configuration Summary

**File .env VPS Production:**
```env
MAIL_MAILER=smtp
MAIL_HOST=mail.vitalife.web.id
MAIL_PORT=587
MAIL_USERNAME=admin@vitalife.web.id
MAIL_PASSWORD=password_email_admin
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@vitalife.web.id
MAIL_FROM_NAME="Vitalife"
```

**✅ Sistem email Vitalife siap untuk production di VPS!**

Semua email booking (Spa, Gym, Yoga) dan welcome email akan dikirim melalui `admin@vitalife.web.id` dengan design yang sudah dibuat sebelumnya.
