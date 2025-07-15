# 🔧 Troubleshooting Guide - Postfix Mail Server Vitalife

## 🚨 Common Issues dan Solutions

### 1. Port 25 Blocked oleh ISP

**Gejala:**
```bash
telnet smtp.gmail.com 25
# Timeout atau connection refused
```

**Solution A - Gunakan Smart Host/Relay:**
```bash
# Edit /etc/postfix/main.cf
sudo nano /etc/postfix/main.cf

# Tambahkan konfigurasi relay (contoh dengan Gmail SMTP)
relayhost = [smtp.gmail.com]:587
smtp_sasl_auth_enable = yes
smtp_sasl_password_maps = hash:/etc/postfix/sasl_passwd
smtp_sasl_security_options = noanonymous
smtp_tls_security_level = encrypt

# Buat file password
sudo nano /etc/postfix/sasl_passwd
# Isi dengan:
[smtp.gmail.com]:587    your-gmail@gmail.com:your-app-password

# Hash password file
sudo postmap /etc/postfix/sasl_passwd
sudo chmod 600 /etc/postfix/sasl_passwd*

# Restart postfix
sudo systemctl restart postfix
```

**Solution B - Gunakan Port Alternatif:**
```bash
# Edit master.cf untuk submission port
sudo nano /etc/postfix/master.cf

# Uncomment atau tambahkan:
submission inet n       -       y       -       -       smtpd
  -o syslog_name=postfix/submission
  -o smtpd_tls_security_level=encrypt
  -o smtpd_sasl_auth_enable=yes
  -o smtpd_tls_auth_only=yes
  -o smtpd_reject_unlisted_recipient=no
  -o smtpd_client_restrictions=permit_sasl_authenticated,reject
  -o milter_macro_daemon_name=ORIGINATING

# Restart postfix
sudo systemctl restart postfix
```

### 2. Permission Issues

**Gejala:**
```
postfix/postdrop[xxx]: warning: unable to look up public/pickup: No such file or directory
```

**Solution:**
```bash
# Fix ownership dan permissions
sudo chown -R postfix:postfix /var/spool/postfix/
sudo chmod -R 755 /var/spool/postfix/
sudo postfix set-permissions
sudo systemctl restart postfix
```

### 3. SSL Certificate Issues

**Gejala:**
```
TLS library problem: error:xxxxxxxx:SSL routines:tls_post_process_client_hello:no shared cipher
```

**Solution:**
```bash
# Check certificate validity
sudo openssl x509 -in /etc/letsencrypt/live/mail.vitalife.web.id/fullchain.pem -text -noout

# Renew certificate manually
sudo certbot renew --force-renewal

# Update Postfix SSL config
sudo nano /etc/postfix/main.cf

# Pastikan paths benar:
smtpd_tls_cert_file = /etc/letsencrypt/live/mail.vitalife.web.id/fullchain.pem
smtpd_tls_key_file = /etc/letsencrypt/live/mail.vitalife.web.id/privkey.pem

# Test SSL
openssl s_client -connect mail.vitalife.web.id:587 -starttls smtp
```

### 4. DNS Issues

**Gejala:**
```
Host or domain name not found. Name service error for name=vitalife.web.id type=MX
```

**Solution:**
```bash
# Check DNS propagation
dig MX vitalife.web.id
nslookup -type=MX vitalife.web.id

# Test dari multiple DNS servers
dig @8.8.8.8 MX vitalife.web.id
dig @1.1.1.1 MX vitalife.web.id

# Verify DNS records
host -t MX vitalife.web.id
host -t A mail.vitalife.web.id
```

**DNS Records yang harus ada:**
```
A Record:
mail.vitalife.web.id -> [IP VPS]

MX Record:
vitalife.web.id -> mail.vitalife.web.id (Priority: 10)

TXT Record (SPF):
vitalife.web.id -> "v=spf1 ip4:[IP_VPS] include:mail.vitalife.web.id ~all"

TXT Record (DMARC):
_dmarc.vitalife.web.id -> "v=DMARC1; p=quarantine; rua=mailto:admin@vitalife.web.id"

TXT Record (DKIM) - Optional:
default._domainkey.vitalife.web.id -> "v=DKIM1; k=rsa; p=[PUBLIC_KEY]"
```

### 5. Mail Queue Issues

**Gejala:**
```
Mail stuck di queue atau tidak terkirim
```

**Solution:**
```bash
# Check mail queue
sudo postqueue -p

# Flush queue (coba kirim ulang)
sudo postfix flush

# Delete specific message
sudo postsuper -d [QUEUE_ID]

# Delete all queued mail
sudo postsuper -d ALL

# Check why mail deferred
sudo postcat -vq [QUEUE_ID]
```

### 6. Authentication Failed

**Gejala:**
```
SASL authentication failed; server mail.vitalife.web.id said: 535 5.7.8 Error: authentication failed
```

**Solution:**
```bash
# Check SASL configuration
sudo nano /etc/postfix/main.cf

# Pastikan konfigurasi SASL benar:
smtpd_sasl_auth_enable = yes
smtpd_sasl_type = dovecot
smtpd_sasl_path = private/auth
smtpd_sasl_security_options = noanonymous

# Check Dovecot auth config
sudo nano /etc/dovecot/conf.d/10-auth.conf

# Test authentication
telnet mail.vitalife.web.id 587
# EHLO test.com
# STARTTLS
# AUTH LOGIN
# [base64 encoded username]
# [base64 encoded password]
```

### 7. Firewall Blocking

**Gejala:**
```
Connection timeout saat connect ke port mail
```

**Solution:**
```bash
# Check firewall status
sudo ufw status
sudo firewall-cmd --list-all

# Open required ports
sudo ufw allow 25/tcp
sudo ufw allow 465/tcp
sudo ufw allow 587/tcp
sudo ufw allow 993/tcp
sudo ufw allow 995/tcp

# Test port connectivity
telnet mail.vitalife.web.id 25
telnet mail.vitalife.web.id 587
nc -zv mail.vitalife.web.id 25
```

### 8. Dovecot Issues

**Gejala:**
```
IMAP/POP3 tidak bisa connect
```

**Solution:**
```bash
# Check Dovecot status
sudo systemctl status dovecot

# Check Dovecot config
sudo dovecot -n

# Test IMAP
telnet mail.vitalife.web.id 993
openssl s_client -connect mail.vitalife.web.id:993

# Check Dovecot logs
sudo tail -f /var/log/dovecot.log
```

## 📊 Monitoring Commands

### Real-time Log Monitoring:
```bash
# Postfix logs
sudo tail -f /var/log/mail.log | grep postfix

# Dovecot logs
sudo tail -f /var/log/mail.log | grep dovecot

# All mail logs
sudo tail -f /var/log/mail.log

# Authentication logs
sudo tail -f /var/log/auth.log | grep -i mail
```

### Queue Management:
```bash
# Show mail queue
sudo postqueue -p

# Show queue summary
sudo qshape

# Show specific message
sudo postcat -vq [QUEUE_ID]

# Show message headers
sudo postcat -hq [QUEUE_ID]
```

### Performance Monitoring:
```bash
# Connection count
sudo netstat -an | grep :25 | wc -l
sudo netstat -an | grep :587 | wc -l

# Process count
ps aux | grep postfix | wc -l
ps aux | grep dovecot | wc -l

# Memory usage
sudo systemctl status postfix
sudo systemctl status dovecot
```

### Security Checks:
```bash
# Check failed login attempts
sudo grep "authentication failed" /var/log/mail.log

# Check rejected emails
sudo grep "REJECT" /var/log/mail.log

# Check suspicious activity
sudo grep -i "warning\|error" /var/log/mail.log
```

## 🔧 Configuration Testing

### Test SMTP without authentication:
```bash
telnet mail.vitalife.web.id 25
EHLO test.com
MAIL FROM: test@vitalife.web.id
RCPT TO: admin@vitalife.web.id
DATA
Subject: Test Email

This is a test email.
.
QUIT
```

### Test SMTP with authentication:
```bash
telnet mail.vitalife.web.id 587
EHLO test.com
STARTTLS
EHLO test.com
AUTH LOGIN
# Base64 encode username: echo -n "admin@vitalife.web.id" | base64
# Base64 encode password: echo -n "password" | base64
```

### Test with PHP mail():
```php
<?php
// Test basic mail() function
$to = "admin@vitalife.web.id";
$subject = "Test from PHP mail()";
$message = "This is a test email from PHP mail() function.";
$headers = "From: noreply@vitalife.web.id\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

if(mail($to, $subject, $message, $headers)) {
    echo "✅ Email sent successfully\n";
} else {
    echo "❌ Email failed to send\n";
}
?>
```

### Test Laravel Mail:
```php
// Laravel Artisan Tinker test
php artisan tinker

use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

$data = [
    'userName' => 'Test User',
    'userEmail' => 'test@example.com',
    'userPhone' => '+62812345678',
    'supportEmail' => 'admin@vitalife.web.id'
];

try {
    Mail::to('admin@vitalife.web.id')->send(new WelcomeEmail($data));
    echo "✅ Laravel email sent successfully\n";
} catch (Exception $e) {
    echo "❌ Laravel email failed: " . $e->getMessage() . "\n";
}
```

## 🚨 Emergency Procedures

### If Mail Server Compromised:
```bash
# Stop services immediately
sudo systemctl stop postfix dovecot

# Check for unauthorized access
sudo grep -i "authentication failed" /var/log/mail.log
sudo grep -i "relay" /var/log/mail.log

# Clear mail queue
sudo postsuper -d ALL

# Change passwords
sudo passwd vitalife

# Update Postfix config to be more restrictive
sudo nano /etc/postfix/main.cf
# Set: smtpd_recipient_restrictions dengan lebih ketat
```

### Backup dan Restore:
```bash
# Backup mail configuration
sudo tar -czf /backup/mail-config-$(date +%Y%m%d).tar.gz \
    /etc/postfix/ \
    /etc/dovecot/ \
    /home/vitalife/Maildir

# Backup mail data
sudo tar -czf /backup/mail-data-$(date +%Y%m%d).tar.gz \
    /home/vitalife/Maildir \
    /var/mail/

# Restore configuration
sudo tar -xzf /backup/mail-config-20241201.tar.gz -C /
sudo systemctl restart postfix dovecot
```

## 📞 Support Resources

### Log Locations:
- Postfix: `/var/log/mail.log`, `/var/log/postfix.log`
- Dovecot: `/var/log/dovecot.log`, `/var/log/mail.log`
- Authentication: `/var/log/auth.log`
- System: `/var/log/syslog`

### Configuration Files:
- Postfix: `/etc/postfix/main.cf`, `/etc/postfix/master.cf`
- Dovecot: `/etc/dovecot/dovecot.conf`, `/etc/dovecot/conf.d/`
- Aliases: `/etc/aliases`
- SSL: `/etc/letsencrypt/live/mail.vitalife.web.id/`

### Useful Commands:
```bash
# Service management
sudo systemctl {start|stop|restart|status} postfix
sudo systemctl {start|stop|restart|status} dovecot

# Configuration check
sudo postfix check
sudo dovecot -n

# Queue management
sudo postqueue -p
sudo postsuper -d ALL
sudo postfix flush

# Log analysis
sudo tail -f /var/log/mail.log
sudo grep "ERROR\|WARNING" /var/log/mail.log
```
