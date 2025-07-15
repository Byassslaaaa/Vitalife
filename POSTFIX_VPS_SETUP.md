# 🚀 Setup Mail Server Postfix di VPS untuk Laravel Vitalife

## 📋 Prerequisites
- VPS dengan Ubuntu 20.04/22.04 atau CentOS 7/8
- Domain yang sudah disetup (contoh: vitalife.web.id)
- Access root/sudo ke VPS
- Firewall yang sudah dikonfigurasi

## 🔧 Step 1: Update System dan Install Postfix

### Ubuntu/Debian:
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Postfix dan mailutils
sudo apt install postfix mailutils -y

# Install SASL untuk authentication
sudo apt install libsasl2-modules sasl2-bin -y
```

### CentOS/RHEL:
```bash
# Update system
sudo yum update -y

# Install Postfix dan mailx
sudo yum install postfix mailx cyrus-sasl-plain -y
```

## 🔧 Step 2: Konfigurasi Postfix

### Edit main configuration file:
```bash
sudo nano /etc/postfix/main.cf
```

### Tambahkan/ubah konfigurasi berikut:
```bash
# Basic Configuration
myhostname = mail.vitalife.web.id
mydomain = vitalife.web.id
myorigin = $mydomain
inet_interfaces = all
mydestination = $myhostname, localhost.$mydomain, localhost, $mydomain

# Network Configuration
mynetworks = 127.0.0.0/8, 10.0.0.0/8, 192.168.0.0/16, 172.16.0.0/12

# Relay Configuration
relayhost = 
relay_domains = 

# SMTP Authentication
smtpd_sasl_auth_enable = yes
smtpd_sasl_type = dovecot
smtpd_sasl_path = private/auth
smtpd_sasl_security_options = noanonymous
smtpd_sasl_local_domain = $myhostname

# TLS Configuration
smtpd_tls_security_level = may
smtpd_tls_cert_file = /etc/ssl/certs/ssl-cert-snakeoil.pem
smtpd_tls_key_file = /etc/ssl/private/ssl-cert-snakeoil.key
smtpd_tls_session_cache_database = btree:${data_directory}/smtpd_scache
smtp_tls_session_cache_database = btree:${data_directory}/smtp_scache

# Security Settings
smtpd_recipient_restrictions = 
    permit_mynetworks,
    permit_sasl_authenticated,
    reject_rbl_client zen.spamhaus.org,
    reject_rbl_client bl.spamcop.net,
    reject_rbl_client cbl.abuseat.org,
    reject_unauth_destination

# Message size limit (25MB)
message_size_limit = 26214400
mailbox_size_limit = 1073741824
```

## 🔧 Step 3: Install dan Konfigurasi Dovecot (untuk IMAP/POP3)

### Install Dovecot:
```bash
# Ubuntu/Debian
sudo apt install dovecot-core dovecot-imapd dovecot-pop3d -y

# CentOS/RHEL
sudo yum install dovecot -y
```

### Konfigurasi Dovecot:
```bash
sudo nano /etc/dovecot/dovecot.conf
```

```bash
# Enable protocols
protocols = imap pop3 lmtp

# Listen on all interfaces
listen = *, ::

# Mail location
mail_location = maildir:~/Maildir

# Authentication
auth_mechanisms = plain login
disable_plaintext_auth = no

# SSL Configuration
ssl = yes
ssl_cert = </etc/ssl/certs/ssl-cert-snakeoil.pem
ssl_key = </etc/ssl/private/ssl-cert-snakeoil.key
```

### Konfigurasi Dovecot Auth:
```bash
sudo nano /etc/dovecot/conf.d/10-auth.conf
```

```bash
# Enable auth for Postfix
service auth {
  unix_listener /var/spool/postfix/private/auth {
    mode = 0666
    user = postfix
    group = postfix
  }
}
```

## 🔧 Step 4: Buat User Email

### Buat user untuk email admin:
```bash
# Buat user sistem
sudo adduser vitalife
sudo passwd vitalife

# Buat direktori maildir
sudo mkdir -p /home/vitalife/Maildir
sudo chown vitalife:vitalife /home/vitalife/Maildir
sudo chmod 700 /home/vitalife/Maildir

# Buat alias untuk admin@vitalife.web.id
echo "admin@vitalife.web.id: vitalife" | sudo tee -a /etc/aliases
echo "support@vitalife.web.id: vitalife" | sudo tee -a /etc/aliases
echo "noreply@vitalife.web.id: vitalife" | sudo tee -a /etc/aliases

# Update aliases database
sudo newaliases
```

## 🔧 Step 5: Konfigurasi DNS Records

### Tambahkan DNS records berikut di domain panel:
```
A Record:
mail.vitalife.web.id -> [IP VPS Anda]

MX Record:
vitalife.web.id -> mail.vitalife.web.id (Priority: 10)

TXT Record (SPF):
vitalife.web.id -> "v=spf1 ip4:[IP_VPS_ANDA] include:mail.vitalife.web.id ~all"

TXT Record (DMARC):
_dmarc.vitalife.web.id -> "v=DMARC1; p=quarantine; rua=mailto:admin@vitalife.web.id"
```

## 🔧 Step 6: Setup SSL Certificate

### Install Certbot:
```bash
# Ubuntu/Debian
sudo apt install certbot -y

# CentOS/RHEL
sudo yum install certbot -y
```

### Generate SSL Certificate:
```bash
# Stop postfix temporarily
sudo systemctl stop postfix

# Generate certificate
sudo certbot certonly --standalone -d mail.vitalife.web.id

# Update Postfix configuration
sudo nano /etc/postfix/main.cf
```

### Update SSL paths in main.cf:
```bash
smtpd_tls_cert_file = /etc/letsencrypt/live/mail.vitalife.web.id/fullchain.pem
smtpd_tls_key_file = /etc/letsencrypt/live/mail.vitalife.web.id/privkey.pem
```

## 🔧 Step 7: Konfigurasi Firewall

```bash
# UFW (Ubuntu)
sudo ufw allow 25/tcp
sudo ufw allow 465/tcp
sudo ufw allow 587/tcp
sudo ufw allow 993/tcp
sudo ufw allow 995/tcp

# Firewall-cmd (CentOS)
sudo firewall-cmd --permanent --add-port=25/tcp
sudo firewall-cmd --permanent --add-port=465/tcp
sudo firewall-cmd --permanent --add-port=587/tcp
sudo firewall-cmd --permanent --add-port=993/tcp
sudo firewall-cmd --permanent --add-port=995/tcp
sudo firewall-cmd --reload
```

## 🔧 Step 8: Start Services

```bash
# Start dan enable services
sudo systemctl start postfix
sudo systemctl enable postfix
sudo systemctl start dovecot
sudo systemctl enable dovecot

# Check status
sudo systemctl status postfix
sudo systemctl status dovecot
```

## 🔧 Step 9: Test Mail Server

### Test dari command line:
```bash
# Test internal mail
echo "Test email from Postfix" | mail -s "Test Subject" admin@vitalife.web.id

# Test dengan telnet
telnet mail.vitalife.web.id 25
```

### Test dengan PHP mail():
```bash
# Buat file test
sudo nano /var/www/html/mailtest.php
```

```php
<?php
$to = "admin@vitalife.web.id";
$subject = "Test Email from VPS";
$message = "Ini adalah test email dari mail server VPS.";
$headers = "From: noreply@vitalife.web.id\r\n";
$headers .= "Reply-To: admin@vitalife.web.id\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

if(mail($to, $subject, $message, $headers)) {
    echo "Email berhasil dikirim!";
} else {
    echo "Email gagal dikirim!";
}
?>
```

## 🔧 Step 10: Konfigurasi Laravel .env

### Update file .env di aplikasi Laravel:
```bash
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mail.vitalife.web.id
MAIL_PORT=587
MAIL_USERNAME=admin@vitalife.web.id
MAIL_PASSWORD=[PASSWORD_USER_VITALIFE]
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@vitalife.web.id
MAIL_FROM_NAME="Vitalife Support"

# Backup Mail Configuration (jika diperlukan)
BACKUP_MAIL_HOST=localhost
BACKUP_MAIL_PORT=25
BACKUP_MAIL_USERNAME=
BACKUP_MAIL_PASSWORD=
```

## 🔧 Step 11: Monitoring dan Maintenance

### Check mail logs:
```bash
# Postfix logs
sudo tail -f /var/log/mail.log
sudo tail -f /var/log/postfix.log

# Dovecot logs
sudo tail -f /var/log/dovecot.log
```

### Mail queue management:
```bash
# Check mail queue
sudo postqueue -p

# Flush mail queue
sudo postfix flush

# Delete all mail in queue
sudo postsuper -d ALL
```

### Backup configuration:
```bash
# Backup Postfix config
sudo cp /etc/postfix/main.cf /etc/postfix/main.cf.backup

# Backup Dovecot config
sudo cp /etc/dovecot/dovecot.conf /etc/dovecot/dovecot.conf.backup
```

## 🔧 Step 12: Troubleshooting

### Common Issues:

1. **Port 25 blocked by ISP:**
```bash
# Test if port 25 is open
telnet smtp.gmail.com 587
# Jika blocked, gunakan port 587 dengan relay
```

2. **Permission issues:**
```bash
sudo chown -R postfix:postfix /var/spool/postfix/
sudo chmod -R 755 /var/spool/postfix/
```

3. **SSL Certificate issues:**
```bash
# Check certificate
sudo openssl x509 -in /etc/letsencrypt/live/mail.vitalife.web.id/fullchain.pem -text -noout
```

4. **DNS propagation:**
```bash
# Check MX record
dig MX vitalife.web.id
nslookup -type=MX vitalife.web.id
```

## 📧 Testing dari Laravel

### Test dengan Artisan:
```bash
# Masuk ke direktori Laravel
cd /path/to/your/laravel/project

# Test email dengan tinker
php artisan tinker
```

```php
// Di dalam tinker
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

$data = [
    'userName' => 'Test User',
    'userEmail' => 'test@example.com',
    'userPhone' => '+62812345678',
    'supportEmail' => 'admin@vitalife.web.id'
];

Mail::to('admin@vitalife.web.id')->send(new WelcomeEmail($data));
```

## 🔄 Auto SSL Renewal

### Setup auto-renewal untuk SSL:
```bash
# Tambahkan cron job
sudo crontab -e

# Tambahkan line berikut:
0 3 * * * /usr/bin/certbot renew --quiet && systemctl reload postfix
```

## 📊 Performance Optimization

### Optimize Postfix:
```bash
# Edit main.cf untuk performance
sudo nano /etc/postfix/main.cf
```

```bash
# Performance settings
default_process_limit = 100
smtpd_client_connection_count_limit = 50
smtpd_client_connection_rate_limit = 30
anvil_rate_time_unit = 60s
```

## ✅ Checklist Setup Completion

- [ ] Postfix installed dan configured
- [ ] Dovecot installed dan configured  
- [ ] DNS records configured (A, MX, SPF, DMARC)
- [ ] SSL certificate installed
- [ ] Firewall ports opened
- [ ] User accounts created
- [ ] Services started dan enabled
- [ ] Mail server tested
- [ ] Laravel .env updated
- [ ] Email testing successful
- [ ] Auto SSL renewal configured
- [ ] Monitoring setup

## 📞 Support

Jika mengalami masalah, check:
1. Log files: `/var/log/mail.log`, `/var/log/postfix.log`
2. Service status: `sudo systemctl status postfix dovecot`
3. Port connectivity: `telnet mail.vitalife.web.id 25`
4. DNS propagation: `dig MX vitalife.web.id`

---
**Note:** Pastikan ISP/VPS provider tidak memblokir port 25. Jika diblokir, gunakan relay SMTP atau port alternatif.
