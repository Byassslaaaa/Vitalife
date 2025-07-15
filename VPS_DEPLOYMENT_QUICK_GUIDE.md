# 🚀 Quick VPS Deployment Guide - Postfix Mail Server Vitalife

## 📋 Ringkasan Setup (30 Menit)

### 1. Download dan Jalankan Script Setup
```bash
# SSH ke VPS Anda
ssh root@your-vps-ip

# Download script setup
wget https://raw.githubusercontent.com/your-repo/vitalife/main/setup_postfix_vitalife.sh
# Atau upload file setup_postfix_vitalife.sh ke VPS

# Jalankan script
chmod +x setup_postfix_vitalife.sh
sudo bash setup_postfix_vitalife.sh
```

### 2. Konfigurasi DNS Records (di Domain Panel)
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

### 3. Update Laravel .env
```bash
# Edit file .env di aplikasi Laravel
MAIL_MAILER=smtp
MAIL_HOST=mail.vitalife.web.id
MAIL_PORT=587
MAIL_USERNAME=admin@vitalife.web.id
MAIL_PASSWORD=VitalifeMail2024!
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@vitalife.web.id
MAIL_FROM_NAME="Vitalife Support"
```

### 4. Test Email System
```bash
# Via Laravel Web Interface
https://vitalife.web.id/admin/mail-server/test

# Via Command Line
echo "Test email" | mail -s "Test Subject" admin@vitalife.web.id

# Via Laravel Artisan
php artisan tinker
Mail::raw('Test email', function($message) { 
    $message->to('admin@vitalife.web.id')->subject('Test'); 
});
```

## 🔧 Manual Setup (jika script gagal)

### Install Packages:
```bash
# Ubuntu/Debian
apt update && apt install -y postfix dovecot-core dovecot-imapd mailutils certbot

# CentOS/RHEL
yum update -y && yum install -y postfix dovecot mailx certbot
```

### Configure Postfix:
```bash
# Edit /etc/postfix/main.cf
myhostname = mail.vitalife.web.id
mydomain = vitalife.web.id
myorigin = $mydomain
inet_interfaces = all
mydestination = $myhostname, localhost.$mydomain, localhost, $mydomain
mynetworks = 127.0.0.0/8, [IP_VPS]/32
relayhost = 
smtpd_sasl_auth_enable = yes
smtpd_tls_security_level = may
```

### Configure Dovecot:
```bash
# Edit /etc/dovecot/dovecot.conf
protocols = imap pop3 lmtp
listen = *, ::
mail_location = maildir:~/Maildir
auth_mechanisms = plain login
ssl = yes
```

### Create User:
```bash
useradd -m vitalife
echo "vitalife:VitalifeMail2024!" | chpasswd
mkdir -p /home/vitalife/Maildir
chown vitalife:vitalife /home/vitalife/Maildir

# Create aliases
echo "admin@vitalife.web.id: vitalife" >> /etc/aliases
newaliases
```

### Setup SSL:
```bash
certbot certonly --standalone -d mail.vitalife.web.id
# Update SSL paths in /etc/postfix/main.cf and /etc/dovecot/dovecot.conf
```

### Start Services:
```bash
systemctl enable postfix dovecot
systemctl start postfix dovecot
```

## 🔍 Troubleshooting Cepat

### Check Service Status:
```bash
systemctl status postfix dovecot
```

### Check Ports:
```bash
netstat -tlnp | grep -E ':25|:587|:993'
```

### View Logs:
```bash
tail -f /var/log/mail.log
```

### Test SMTP:
```bash
telnet mail.vitalife.web.id 587
# EHLO test.com
# QUIT
```

### Check DNS:
```bash
dig MX vitalife.web.id
dig A mail.vitalife.web.id
```

## 🚨 Common Issues

### Port 25 Blocked:
- Gunakan port 587 untuk submission
- Atau setup SMTP relay dengan Gmail/SendGrid

### SSL Issues:
```bash
# Renew certificate
certbot renew --force-renewal
systemctl restart postfix dovecot
```

### Permission Issues:
```bash
chown -R postfix:postfix /var/spool/postfix/
postfix set-permissions
```

### Authentication Failed:
- Check user credentials
- Verify Dovecot auth configuration
- Test with: `telnet mail.vitalife.web.id 587`

## ✅ Verification Checklist

- [ ] Postfix service running
- [ ] Dovecot service running
- [ ] Ports 25, 587, 993 open
- [ ] SSL certificate valid
- [ ] DNS records configured
- [ ] User accounts created
- [ ] Laravel .env updated
- [ ] Test emails successful
- [ ] Web interface working: https://vitalife.web.id/admin/mail-server/test

## 📞 Support Commands

```bash
# Monitor script
bash monitor_mail_server.sh

# Check queue
postqueue -p

# Flush queue
postfix flush

# View config
postconf -n
dovecot -n

# Test mail sending
echo "Test" | mail -s "Subject" admin@vitalife.web.id
```

## 🔄 Maintenance

### Daily:
- Check mail queue: `postqueue -p`
- Monitor logs: `tail /var/log/mail.log`

### Weekly:
- Check SSL expiry
- Review authentication logs
- Monitor disk space

### Monthly:
- Update packages
- Review security logs
- Backup configurations

---
**✅ Setup berhasil jika:**
1. Services running: `systemctl status postfix dovecot`
2. Ports accessible: `telnet mail.vitalife.web.id 587`
3. DNS resolving: `dig MX vitalife.web.id`
4. Laravel emails working: Web test interface success
5. No errors in: `/var/log/mail.log`
