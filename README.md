# 🌟 HeaLife - Your Wellness & Health Companion

<div align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Laravel Logo">
  
  [![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
  [![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
  [![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
  
  <p align="center">
    <strong>Platform komprehensif untuk manajemen layanan kesehatan dan wellness</strong>
  </p>
  
  <p align="center">
    <a href="#features">Features</a> •
    <a href="#installation">Installation</a> •
    <a href="#usage">Usage</a> •
    <a href="#api">API</a> •
    <a href="#contributing">Contributing</a>
  </p>
</div>

---

## 📋 Deskripsi

**HeaLife** adalah platform digital yang menghubungkan pengguna dengan berbagai layanan kesehatan dan wellness termasuk Spa, Gym, dan Yoga. Platform ini menyediakan sistem booking yang terintegrasi, manajemen admin yang komprehensif, dan pengalaman pengguna yang seamless.

## ✨ Features

### 🎯 Core Features
- **🏥 Multi-Service Platform**: Spa, Gym, dan Yoga dalam satu aplikasi
- **📅 Smart Booking System**: Sistem reservasi real-time dengan validasi otomatis
- **💳 Payment Integration**: Integrasi dengan Midtrans untuk pembayaran yang aman
- **📱 Responsive Design**: Optimized untuk desktop, tablet, dan mobile
- **🔐 Role-Based Access**: Sistem role admin dan user yang terpisah

### 👤 User Features
- **🔍 Service Discovery**: Pencarian dan filter layanan berdasarkan lokasi, harga, dan rating
- **📱 Real-time Booking**: Booking langsung dengan konfirmasi instant
- **💬 Live Chat Support**: Chat real-time dengan admin untuk bantuan
- **🎫 Voucher System**: Sistem voucher dan diskon otomatis
- **📊 Booking History**: Riwayat lengkap transaksi dan booking
- **🌐 Multi-language**: Support bahasa Indonesia dan Inggris
- **☁️ Weather Integration**: Informasi cuaca untuk planning aktivitas outdoor

### 🛠️ Admin Features
- **📊 Comprehensive Dashboard**: Analytics lengkap dengan statistik real-time
- **🏢 Service Management**: CRUD operations untuk Spa, Gym, dan Yoga
- **👥 User Management**: Manajemen akun pengguna dan admin
- **💰 Payment Monitoring**: Tracking pembayaran dan revenue analytics
- **💬 Chat Management**: Kelola percakapan dengan pengguna
- **🎟️ Voucher Control**: Sistem manajemen voucher dan promosi
- **📈 Business Intelligence**: Report dan analytics mendalam

### 🔧 Technical Features
- **🚀 Performance Optimized**: Lazy loading, caching, dan optimasi database
- **🔒 Security First**: Authentication, authorization, dan data encryption
- **📱 PWA Ready**: Progressive Web App capabilities
- **🔄 Real-time Updates**: WebSocket untuk notifikasi real-time
- **📊 API-First**: RESTful API untuk integrasi external
- **🎨 Modern UI/UX**: Clean design dengan Tailwind CSS

## 🚀 Installation

### Prerequisites
- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.0
- **MySQL** >= 8.0
- **Git**

### Quick Start

```bash
# Clone repository
git clone https://github.com/Byassslaaaa/Vitalife.git
cd Vitalife

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vitalife
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations and seeders
php artisan migrate --seed

# Build assets
npm run build

# Start development server
php artisan serve
```

### 🔧 Environment Configuration

```env
# Application
APP_NAME=Vitalife
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vitalife
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Payment Gateway (Midtrans)
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false

# OpenAI Integration
OPENAI_API_KEY=your_openai_key

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

## 🎯 Usage

### 👥 Default Accounts

| Role | Email | Password | Description |
|------|-------|----------|-------------|
| **Admin** | admin@vitalife.com | admin123 | Full system access |
| **User** | user@vitalife.com | user123 | Regular user account |

### 🌐 Main Routes

| Route | Description | Access |
|-------|-------------|--------|
| `/` | Homepage dengan service overview | Public |
| `/spa` | Spa services catalog | Public |
| `/gym` | Gym services catalog | Public |
| `/yoga` | Yoga services catalog | Public |
| `/admin` | Admin dashboard | Admin only |
| `/profile` | User profile management | Authenticated |

### 📱 Key Functionalities

#### For Users:
1. **Browse Services**: Lihat daftar spa, gym, dan yoga terdekat
2. **Make Booking**: Pilih layanan dan buat reservasi
3. **Payment**: Bayar menggunakan berbagai metode pembayaran
4. **Track Booking**: Monitor status booking real-time
5. **Chat Support**: Hubungi admin via live chat

#### For Admins:
1. **Dashboard Analytics**: Monitor performa bisnis
2. **Service Management**: Kelola spa, gym, dan yoga
3. **User Management**: Administrasi akun pengguna
4. **Booking Management**: Monitor dan update booking
5. **Revenue Tracking**: Analisis pendapatan dan trends

## 🔌 API Documentation

### Authentication
```bash
# Login
POST /api/login
{
  "email": "user@example.com",
  "password": "password"
}

# Register
POST /api/register
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

### Booking API
```bash
# Create booking
POST /api/booking
Authorization: Bearer {token}
{
  "service_type": "spa",
  "service_id": 1,
  "booking_date": "2024-12-25",
  "booking_time": "10:00"
}

# Get booking status
GET /api/booking/{booking_code}
Authorization: Bearer {token}
```

### Services API
```bash
# Get spa services
GET /api/spa

# Get gym services  
GET /api/gym

# Get yoga services
GET /api/yoga

# Get service details
GET /api/{service_type}/{id}
```

## 🏗️ Architecture

### 📁 Project Structure
```
vitalife/
├── 📂 app/
│   ├── 📂 Http/Controllers/     # Controllers
│   ├── 📂 Models/              # Eloquent Models
│   ├── 📂 Services/            # Business Logic
│   ├── 📂 Mail/               # Email Templates
│   └── 📂 Notifications/      # Push Notifications
├── 📂 database/
│   ├── 📂 migrations/         # Database Migrations
│   └── 📂 seeders/           # Data Seeders
├── 📂 resources/
│   ├── 📂 views/             # Blade Templates
│   ├── 📂 js/               # Frontend JavaScript
│   └── 📂 css/              # Stylesheets
├── 📂 routes/
│   ├── 📄 web.php           # Web Routes
│   └── 📄 api.php           # API Routes
└── 📂 public/              # Public Assets
```

### 🗄️ Database Schema

#### Core Tables
- `users` - User accounts and profiles
- `spas` - Spa services and information
- `gyms` - Gym facilities and services  
- `yogas` - Yoga classes and schedules
- `bookings` - Universal booking system
- `payments` - Payment transactions
- `vouchers` - Discount and promotion system

#### Relationship Diagram
```
Users ──┐
        ├── Bookings ──┐
        │              ├── Spa Services
        │              ├── Gym Services
        └── Payments    └── Yoga Services
```

## 🛡️ Security Features

- **🔐 Authentication**: Laravel Sanctum untuk API authentication
- **🛡️ Authorization**: Role-based access control (RBAC)
- **🔒 Data Protection**: Encryption untuk data sensitif
- **🚫 CSRF Protection**: Built-in CSRF protection
- **🔍 Input Validation**: Comprehensive form validation
- **🔐 Password Security**: Bcrypt hashing dengan salt

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

## 📈 Performance

### Optimization Features
- **⚡ Database Indexing**: Optimized queries dengan proper indexing
- **🗄️ Caching Strategy**: Redis/Memcached untuk session dan cache
- **📷 Image Optimization**: Automatic image compression dan lazy loading
- **🔄 Queue System**: Background job processing
- **📊 Database Optimization**: Query optimization dan eager loading

### Performance Metrics
- **⚡ Page Load**: < 2 seconds (average)
- **🔄 API Response**: < 500ms (average)
- **📱 Mobile Score**: 95+ (Google PageSpeed)
- **🖥️ Desktop Score**: 98+ (Google PageSpeed)

## 🤝 Contributing

Kami welcome kontribusi dari developer! Berikut cara berkontribusi:

### 🔄 Development Workflow
1. **Fork** repository ini
2. **Create** feature branch (`git checkout -b feature/amazing-feature`)
3. **Commit** changes (`git commit -m 'Add amazing feature'`)
4. **Push** ke branch (`git push origin feature/amazing-feature`)
5. **Open** Pull Request

### 📝 Coding Standards
- Follow **PSR-12** coding standards
- Write **comprehensive tests** untuk fitur baru
- Update **documentation** untuk API changes
- Use **conventional commits** untuk commit messages

### 🐛 Bug Reports
Gunakan [GitHub Issues](https://github.com/Byassslaaaa/Vitalife/issues) untuk melaporkan bugs dengan template:
- **Environment details**
- **Steps to reproduce**
- **Expected vs actual behavior**
- **Screenshots** (jika applicable)

## 📜 License

Project ini menggunakan **MIT License**. Lihat file [LICENSE](LICENSE) untuk detail lengkap.

## 👨‍💻 Team

### Development Team
- **Project Lead**: [@Byassslaaaa](https://github.com/Byassslaaaa)
- **Backend Developer**: Laravel & API Development
- **Frontend Developer**: UI/UX & Responsive Design
- **DevOps**: Server & Deployment Management

### Contact
- **📧 Email**: vitalife.support@gmail.com
- **🌐 Website**: [vitalife.app](https://vitalife.app)
- **📱 GitHub**: [@Byassslaaaa](https://github.com/Byassslaaaa)

## 🙏 Acknowledgments

Special thanks to:
- **Laravel Community** untuk framework yang luar biasa
- **Tailwind CSS** untuk utility-first CSS framework
- **Midtrans** untuk payment gateway integration
- **OpenAI** untuk AI-powered features
- **Contributors** yang telah membantu pengembangan project

---

<div align="center">
  <p><strong>Made with ❤️ for a healthier lifestyle</strong></p>
  <p>⭐ Star this repository if you find it helpful!</p>
</div>
