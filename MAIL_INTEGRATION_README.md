# Vitalife Mail System Integration Guide

## 📧 Mail System Overview

Sistem mail Vitalife telah diintegrasikan dengan SMTP untuk mengirim notifikasi email booking dan welcome email. Sistem ini mendukung semua jenis layanan: Gym, Spa, dan Yoga.

## 🚀 Mail Classes Available

### 1. WelcomeEmail
Dikirim saat user baru melakukan registrasi.
```php
$userData = [
    'userName' => 'Nama User',
    'userEmail' => 'user@example.com',
    'userPhone' => '+62 812-3456-7890',
    'supportEmail' => 'support@vitalife.com'
];
Mail::to($email)->send(new WelcomeEmail($userData));
```

### 2. BookingSuccessMail
Email konfirmasi booking general.
```php
$bookingData = [
    'bookingCode' => 'VTL-ABC123',
    'customerName' => 'Nama Customer',
    'customerEmail' => 'customer@example.com',
    'bookingDate' => '2024-01-15',
    'bookingTime' => '10:00',
    'totalAmount' => '150.000',
    'status' => 'confirmed',
    'paymentStatus' => 'paid',
    'paymentMethod' => 'Credit Card',
    'notes' => 'Catatan booking',
    'supportEmail' => 'support@vitalife.com'
];
Mail::to($email)->send(new BookingSuccessMail($bookingData));
```

### 3. YogaBookingSuccessMail
Email konfirmasi booking yoga dengan data spesifik yoga.
```php
$yogaData = [
    'bookingCode' => 'YOGA-ABC123',
    'customerName' => 'Nama Peserta',
    'customerEmail' => 'peserta@example.com',
    'bookingDate' => '2024-01-15',
    'bookingTime' => '07:00',
    'classType' => 'Hatha Yoga',
    'participants' => 1,
    'specialRequests' => 'Please provide yoga mat',
    'totalAmount' => '100.000',
    'status' => 'confirmed',
    'paymentStatus' => 'paid',
    'paymentMethod' => 'Bank Transfer',
    'supportEmail' => 'support@vitalife.com'
];
Mail::to($email)->send(new YogaBookingSuccessMail($yogaData));
```

### 4. SpaBookingSuccessMail
Email konfirmasi booking spa dengan data spesifik spa.
```php
$spaData = [
    'bookingCode' => 'SPA-ABC123',
    'customerName' => 'Nama Tamu',
    'customerEmail' => 'tamu@example.com',
    'bookingDate' => '2024-01-15',
    'bookingTime' => '14:00',
    'duration' => 90,
    'therapistPreference' => 'Female Therapist',
    'totalAmount' => '350.000',
    'status' => 'confirmed',
    'paymentStatus' => 'paid',
    'paymentMethod' => 'Cash',
    'notes' => 'Relaxing massage therapy',
    'supportEmail' => 'support@vitalife.com'
];
Mail::to($email)->send(new SpaBookingSuccessMail($spaData));
```

### 5. GymBookingSuccessMail
Email konfirmasi booking gym dengan data spesifik gym.
```php
$gymData = [
    'bookingCode' => 'GYM-ABC123',
    'customerName' => 'Nama Member',
    'customerEmail' => 'member@example.com',
    'bookingDate' => '2024-01-15',
    'bookingTime' => '18:00',
    'duration' => 2,
    'totalAmount' => '75.000',
    'status' => 'confirmed',
    'paymentStatus' => 'paid',
    'paymentMethod' => 'E-Wallet',
    'notes' => 'Evening workout session',
    'supportEmail' => 'support@vitalife.com'
];
Mail::to($email)->send(new GymBookingSuccessMail($gymData));
```

## ⚙️ SMTP Configuration

### 1. Environment Variables
Update file `.env` dengan konfigurasi SMTP:

```env
# Gmail SMTP Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@vitalife.com
MAIL_FROM_NAME="Vitalife"

# VPS SMTP Configuration (Alternative)
# MAIL_MAILER=smtp
# MAIL_HOST=your-vps-smtp-host
# MAIL_PORT=587
# MAIL_USERNAME=your_vps_email
# MAIL_PASSWORD=your_vps_password
# MAIL_ENCRYPTION=tls
```

### 2. Local Development (MailHog)
Untuk testing local dengan MailHog:
```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

## 🧪 Testing Email System

### Artisan Command untuk Testing
Gunakan command yang telah dibuat untuk testing email:

```bash
# Test welcome email
php artisan email:test welcome your_email@example.com

# Test general booking email
php artisan email:test booking your_email@example.com

# Test yoga booking email
php artisan email:test yoga your_email@example.com

# Test spa booking email
php artisan email:test spa your_email@example.com

# Test gym booking email
php artisan email:test gym your_email@example.com
```

### Manual Testing via Tinker
```bash
php artisan tinker
```

```php
// Test Welcome Email
$userData = [
    'userName' => 'Test User',
    'userEmail' => 'test@example.com',
    'userPhone' => '+62 812-3456-7890',
    'supportEmail' => 'support@vitalife.com'
];
Mail::to('test@example.com')->send(new App\Mail\WelcomeEmail($userData));

// Test Booking Email
$bookingData = [
    'bookingCode' => 'TEST-' . strtoupper(uniqid()),
    'customerName' => 'Test Customer',
    'customerEmail' => 'test@example.com',
    'bookingDate' => now()->addDays(1)->format('Y-m-d'),
    'bookingTime' => '10:00',
    'totalAmount' => '150.000',
    'status' => 'confirmed',
    'paymentStatus' => 'paid',
    'paymentMethod' => 'Credit Card',
    'notes' => 'Test booking',
    'supportEmail' => 'support@vitalife.com'
];
Mail::to('test@example.com')->send(new App\Mail\BookingSuccessMail($bookingData));
```

## 🎨 Email Templates

Email templates terletak di `resources/views/emails/` dengan design yang responsif dan modern:

- `welcome.blade.php` - Welcome email template
- `booking_success.blade.php` - General booking confirmation
- `yoga_booking_success.blade.php` - Yoga-specific booking confirmation
- `spa_booking_success.blade.php` - Spa-specific booking confirmation
- `gym_booking_success.blade.php` - Gym-specific booking confirmation

## 📋 Features Email Templates

### ✨ Design Features
- 📱 **Responsive Design** - Works on all devices
- 🎨 **Modern UI** - Beautiful gradient headers and cards
- 🌈 **Service-Specific Colors** - Different colors for Gym, Spa, Yoga
- 📊 **Structured Layout** - Easy to read information grid
- 🎯 **Clear Call-to-Actions** - Prominent booking codes and instructions

### 🔧 Content Features
- 📋 **Complete Booking Information** - All essential details included
- 💡 **Helpful Tips** - Service-specific preparation tips
- ⚠️ **Policy Information** - Cancellation and attendance policies
- 📞 **Contact Information** - Multiple ways to reach support
- 🎁 **Welcome Bonuses** - Special offers for new users (welcome email)

## 🔧 Integration with Controllers

### Example Implementation in BookingController
```php
use App\Mail\YogaBookingSuccessMail;
use Illuminate\Support\Facades\Mail;

public function confirmYogaBooking(Request $request)
{
    // Process booking...
    $booking = YogaBooking::create($data);
    
    // Send confirmation email
    $emailData = [
        'bookingCode' => $booking->booking_code,
        'customerName' => $booking->customer_name,
        'customerEmail' => $booking->customer_email,
        'bookingDate' => $booking->booking_date,
        'bookingTime' => $booking->booking_time,
        'classType' => $booking->class_type,
        'participants' => $booking->participants,
        'totalAmount' => number_format($booking->total_amount, 0, ',', '.'),
        'status' => $booking->status,
        'paymentStatus' => $booking->payment_status,
        'paymentMethod' => $booking->payment_method,
        'specialRequests' => $booking->special_requests,
        'supportEmail' => env('MAIL_FROM_ADDRESS', 'support@vitalife.com')
    ];
    
    Mail::to($booking->customer_email)->send(new YogaBookingSuccessMail($emailData));
    
    return response()->json(['message' => 'Booking confirmed and email sent']);
}
```

## 📊 Mail Queue (Optional)

Untuk performa yang lebih baik, gunakan queue untuk email:

1. **Setup Queue**:
```bash
php artisan queue:table
php artisan migrate
```

2. **Update .env**:
```env
QUEUE_CONNECTION=database
```

3. **Send Email with Queue**:
```php
Mail::to($email)->queue(new YogaBookingSuccessMail($data));
```

4. **Run Queue Worker**:
```bash
php artisan queue:work
```

## 🐛 Troubleshooting

### Common Issues

1. **SMTP Authentication Failed**
   - Check username/password
   - Use App Password for Gmail
   - Verify SMTP host and port

2. **Template Not Found**
   - Ensure blade files exist in `resources/views/emails/`
   - Check file permissions

3. **Data Not Displaying**
   - Verify array keys match template variables
   - Check for null values

4. **SSL/TLS Issues**
   - Try different encryption: `tls` or `ssl`
   - Check firewall settings

### Debug Mode
Enable mail logging in `.env`:
```env
MAIL_MAILER=log
LOG_CHANNEL=single
```

Emails will be logged in `storage/logs/laravel.log`

## 🎯 Production Deployment

### VPS SMTP Setup
1. Configure postfix or use external SMTP service
2. Update DNS records (SPF, DKIM, DMARC)
3. Test email deliverability
4. Monitor email logs

### Monitoring
- Check `storage/logs/` for email errors
- Monitor SMTP server performance
- Track email delivery rates

---

**🎉 Your Vitalife mail system is now ready for production!**

Semua mail classes telah dikonfigurasi dengan SMTP, templates sudah dibuat dengan design yang menarik, dan testing command tersedia untuk memastikan semuanya berfungsi dengan baik.
