#!/bin/bash

# 🚀 Script Automasi Setup Postfix Mail Server untuk Laravel Vitalife
# Author: GitHub Copilot
# Version: 1.0
# Usage: sudo bash setup_postfix_vitalife.sh

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
DOMAIN="vitalife.web.id"
MAIL_SUBDOMAIN="mail.vitalife.web.id"
ADMIN_EMAIL="admin@vitalife.web.id"
SUPPORT_EMAIL="support@vitalife.web.id"
NOREPLY_EMAIL="noreply@vitalife.web.id"

echo -e "${BLUE}🚀 Vitalife Mail Server Setup Script${NC}"
echo -e "${BLUE}====================================${NC}"

# Function to print status
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if running as root
if [[ $EUID -ne 0 ]]; then
   print_error "Script ini harus dijalankan sebagai root"
   echo "Gunakan: sudo bash setup_postfix_vitalife.sh"
   exit 1
fi

# Detect OS
if [ -f /etc/os-release ]; then
    . /etc/os-release
    OS=$NAME
    VER=$VERSION_ID
else
    print_error "Cannot detect OS"
    exit 1
fi

print_status "Detected OS: $OS $VER"

# Function to install packages based on OS
install_packages() {
    print_status "Installing required packages..."

    if [[ "$OS" == *"Ubuntu"* ]] || [[ "$OS" == *"Debian"* ]]; then
        apt update
        apt install -y postfix dovecot-core dovecot-imapd dovecot-pop3d mailutils libsasl2-modules sasl2-bin certbot ufw
    elif [[ "$OS" == *"CentOS"* ]] || [[ "$OS" == *"Red Hat"* ]]; then
        yum update -y
        yum install -y postfix dovecot mailx cyrus-sasl-plain certbot firewalld
    else
        print_error "Unsupported OS: $OS"
        exit 1
    fi

    print_status "Packages installed successfully"
}

# Function to configure Postfix
configure_postfix() {
    print_status "Configuring Postfix..."

    # Backup original config
    cp /etc/postfix/main.cf /etc/postfix/main.cf.backup.$(date +%Y%m%d_%H%M%S)

    # Get server IP
    SERVER_IP=$(curl -s http://ipecho.net/plain)

    cat > /etc/postfix/main.cf << EOF
# Basic Configuration
myhostname = $MAIL_SUBDOMAIN
mydomain = $DOMAIN
myorigin = \$mydomain
inet_interfaces = all
mydestination = \$myhostname, localhost.\$mydomain, localhost, \$mydomain

# Network Configuration
mynetworks = 127.0.0.0/8, 10.0.0.0/8, 192.168.0.0/16, 172.16.0.0/12, $SERVER_IP/32

# Relay Configuration
relayhost =
relay_domains =

# SMTP Authentication
smtpd_sasl_auth_enable = yes
smtpd_sasl_type = dovecot
smtpd_sasl_path = private/auth
smtpd_sasl_security_options = noanonymous
smtpd_sasl_local_domain = \$myhostname

# TLS Configuration
smtpd_tls_security_level = may
smtpd_tls_cert_file = /etc/ssl/certs/ssl-cert-snakeoil.pem
smtpd_tls_key_file = /etc/ssl/private/ssl-cert-snakeoil.key
smtpd_tls_session_cache_database = btree:\${data_directory}/smtpd_scache
smtp_tls_session_cache_database = btree:\${data_directory}/smtp_scache

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

# Performance settings
default_process_limit = 100
smtpd_client_connection_count_limit = 50
smtpd_client_connection_rate_limit = 30
anvil_rate_time_unit = 60s
EOF

    print_status "Postfix configuration completed"
}

# Function to configure Dovecot
configure_dovecot() {
    print_status "Configuring Dovecot..."

    # Backup original config
    cp /etc/dovecot/dovecot.conf /etc/dovecot/dovecot.conf.backup.$(date +%Y%m%d_%H%M%S)

    cat > /etc/dovecot/dovecot.conf << EOF
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

# Postfix auth
service auth {
  unix_listener /var/spool/postfix/private/auth {
    mode = 0666
    user = postfix
    group = postfix
  }
}
EOF

    print_status "Dovecot configuration completed"
}

# Function to create email users
create_email_users() {
    print_status "Creating email users..."

    # Create vitalife user
    if ! id "vitalife" &>/dev/null; then
        useradd -m -s /bin/bash vitalife
        print_status "User 'vitalife' created"

        # Set password
        echo "vitalife:VitalifeMail2024!" | chpasswd
        print_status "Password set for vitalife user"
    else
        print_status "User 'vitalife' already exists"
    fi

    # Create maildir
    mkdir -p /home/vitalife/Maildir
    chown vitalife:vitalife /home/vitalife/Maildir
    chmod 700 /home/vitalife/Maildir

    # Create aliases
    cat >> /etc/aliases << EOF
admin@$DOMAIN: vitalife
support@$DOMAIN: vitalife
noreply@$DOMAIN: vitalife
postmaster@$DOMAIN: vitalife
abuse@$DOMAIN: vitalife
EOF

    newaliases
    print_status "Email aliases created"
}

# Function to setup SSL
setup_ssl() {
    print_status "Setting up SSL certificate..."

    # Stop services temporarily
    systemctl stop postfix dovecot 2>/dev/null || true

    # Generate certificate
    if [ ! -d "/etc/letsencrypt/live/$MAIL_SUBDOMAIN" ]; then
        certbot certonly --standalone -d $MAIL_SUBDOMAIN --non-interactive --agree-tos --email $ADMIN_EMAIL

        if [ $? -eq 0 ]; then
            print_status "SSL certificate generated successfully"

            # Update Postfix config
            sed -i "s|smtpd_tls_cert_file = /etc/ssl/certs/ssl-cert-snakeoil.pem|smtpd_tls_cert_file = /etc/letsencrypt/live/$MAIL_SUBDOMAIN/fullchain.pem|" /etc/postfix/main.cf
            sed -i "s|smtpd_tls_key_file = /etc/ssl/private/ssl-cert-snakeoil.key|smtpd_tls_key_file = /etc/letsencrypt/live/$MAIL_SUBDOMAIN/privkey.pem|" /etc/postfix/main.cf

            # Update Dovecot config
            sed -i "s|ssl_cert = </etc/ssl/certs/ssl-cert-snakeoil.pem|ssl_cert = </etc/letsencrypt/live/$MAIL_SUBDOMAIN/fullchain.pem|" /etc/dovecot/dovecot.conf
            sed -i "s|ssl_key = </etc/ssl/private/ssl-cert-snakeoil.key|ssl_key = </etc/letsencrypt/live/$MAIL_SUBDOMAIN/privkey.pem|" /etc/dovecot/dovecot.conf

            print_status "SSL configuration updated"
        else
            print_warning "SSL certificate generation failed, using self-signed certificate"
        fi
    else
        print_status "SSL certificate already exists"
    fi
}

# Function to configure firewall
configure_firewall() {
    print_status "Configuring firewall..."

    if [[ "$OS" == *"Ubuntu"* ]] || [[ "$OS" == *"Debian"* ]]; then
        ufw --force enable
        ufw allow 25/tcp
        ufw allow 465/tcp
        ufw allow 587/tcp
        ufw allow 993/tcp
        ufw allow 995/tcp
        ufw allow 80/tcp
        ufw allow 443/tcp
        ufw allow 22/tcp
    elif [[ "$OS" == *"CentOS"* ]] || [[ "$OS" == *"Red Hat"* ]]; then
        systemctl enable firewalld
        systemctl start firewalld
        firewall-cmd --permanent --add-port=25/tcp
        firewall-cmd --permanent --add-port=465/tcp
        firewall-cmd --permanent --add-port=587/tcp
        firewall-cmd --permanent --add-port=993/tcp
        firewall-cmd --permanent --add-port=995/tcp
        firewall-cmd --permanent --add-port=80/tcp
        firewall-cmd --permanent --add-port=443/tcp
        firewall-cmd --reload
    fi

    print_status "Firewall configured"
}

# Function to start services
start_services() {
    print_status "Starting mail services..."

    systemctl enable postfix dovecot
    systemctl start postfix dovecot

    # Check service status
    if systemctl is-active --quiet postfix && systemctl is-active --quiet dovecot; then
        print_status "Mail services started successfully"
    else
        print_error "Failed to start mail services"
        exit 1
    fi
}

# Function to setup auto SSL renewal
setup_ssl_renewal() {
    print_status "Setting up SSL auto-renewal..."

    # Add cron job for SSL renewal
    (crontab -l 2>/dev/null; echo "0 3 * * * /usr/bin/certbot renew --quiet && systemctl reload postfix dovecot") | crontab -

    print_status "SSL auto-renewal configured"
}

# Function to test mail server
test_mail_server() {
    print_status "Testing mail server..."

    # Test internal connectivity
    if nc -z localhost 25 >/dev/null 2>&1; then
        print_status "✅ SMTP port 25 is accessible"
    else
        print_warning "❌ SMTP port 25 is not accessible"
    fi

    if nc -z localhost 587 >/dev/null 2>&1; then
        print_status "✅ SMTP port 587 is accessible"
    else
        print_warning "❌ SMTP port 587 is not accessible"
    fi

    # Test mail sending
    echo "Test email from Postfix setup script" | mail -s "Vitalife Mail Server Test" $ADMIN_EMAIL 2>/dev/null || true
    print_status "Test email sent to $ADMIN_EMAIL"
}

# Function to display final configuration
display_final_config() {
    SERVER_IP=$(curl -s http://ipecho.net/plain)

    echo -e "\n${GREEN}🎉 Mail Server Setup Completed!${NC}"
    echo -e "${BLUE}================================${NC}"
    echo -e "${YELLOW}Server Information:${NC}"
    echo -e "Domain: $DOMAIN"
    echo -e "Mail Server: $MAIL_SUBDOMAIN"
    echo -e "Server IP: $SERVER_IP"
    echo -e "\n${YELLOW}Email Accounts:${NC}"
    echo -e "Admin: $ADMIN_EMAIL"
    echo -e "Support: $SUPPORT_EMAIL"
    echo -e "No-Reply: $NOREPLY_EMAIL"
    echo -e "Username: vitalife"
    echo -e "Password: VitalifeMail2024!"
    echo -e "\n${YELLOW}Laravel .env Configuration:${NC}"
    echo -e "MAIL_MAILER=smtp"
    echo -e "MAIL_HOST=$MAIL_SUBDOMAIN"
    echo -e "MAIL_PORT=587"
    echo -e "MAIL_USERNAME=$ADMIN_EMAIL"
    echo -e "MAIL_PASSWORD=VitalifeMail2024!"
    echo -e "MAIL_ENCRYPTION=tls"
    echo -e "MAIL_FROM_ADDRESS=$ADMIN_EMAIL"
    echo -e "MAIL_FROM_NAME=\"Vitalife Support\""
    echo -e "\n${YELLOW}Required DNS Records:${NC}"
    echo -e "A Record: $MAIL_SUBDOMAIN -> $SERVER_IP"
    echo -e "MX Record: $DOMAIN -> $MAIL_SUBDOMAIN (Priority: 10)"
    echo -e "TXT Record (SPF): $DOMAIN -> \"v=spf1 ip4:$SERVER_IP include:$MAIL_SUBDOMAIN ~all\""
    echo -e "TXT Record (DMARC): _dmarc.$DOMAIN -> \"v=DMARC1; p=quarantine; rua=mailto:$ADMIN_EMAIL\""
    echo -e "\n${YELLOW}Testing:${NC}"
    echo -e "Test web interface: https://vitalife.web.id/admin/email-test"
    echo -e "Check logs: sudo tail -f /var/log/mail.log"
    echo -e "Check queue: sudo postqueue -p"
    echo -e "\n${GREEN}Setup completed successfully!${NC}"
}

# Main execution
main() {
    print_status "Starting Vitalife Mail Server setup..."

    install_packages
    configure_postfix
    configure_dovecot
    create_email_users
    configure_firewall
    setup_ssl
    start_services
    setup_ssl_renewal
    test_mail_server
    display_final_config

    print_status "Mail server setup completed! Please configure DNS records and test email functionality."
}

# Run main function
main "$@"
