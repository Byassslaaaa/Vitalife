#!/bin/bash

# 📊 Mail Server Monitoring Script untuk Vitalife
# Author: GitHub Copilot
# Version: 1.0
# Usage: bash monitor_mail_server.sh

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
MAIL_HOST="mail.vitalife.web.id"
ADMIN_EMAIL="admin@vitalife.web.id"
LOG_FILE="/var/log/mail_monitor.log"

# Function to log with timestamp
log_message() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') - $1" | tee -a $LOG_FILE
}

# Function to check service status
check_service() {
    local service=$1
    if systemctl is-active --quiet $service; then
        echo -e "${GREEN}✅ $service is running${NC}"
        log_message "INFO: $service is running"
        return 0
    else
        echo -e "${RED}❌ $service is not running${NC}"
        log_message "ERROR: $service is not running"
        return 1
    fi
}

# Function to check port connectivity
check_port() {
    local port=$1
    local description=$2
    if nc -z localhost $port >/dev/null 2>&1; then
        echo -e "${GREEN}✅ Port $port ($description) is open${NC}"
        log_message "INFO: Port $port ($description) is open"
        return 0
    else
        echo -e "${RED}❌ Port $port ($description) is closed${NC}"
        log_message "ERROR: Port $port ($description) is closed"
        return 1
    fi
}

# Function to check SSL certificate
check_ssl() {
    echo -e "${BLUE}🔒 Checking SSL Certificate${NC}"

    if [ -f "/etc/letsencrypt/live/$MAIL_HOST/fullchain.pem" ]; then
        EXPIRY=$(openssl x509 -in /etc/letsencrypt/live/$MAIL_HOST/fullchain.pem -noout -dates | grep notAfter | cut -d= -f2)
        EXPIRY_EPOCH=$(date -d "$EXPIRY" +%s)
        CURRENT_EPOCH=$(date +%s)
        DAYS_LEFT=$(( ($EXPIRY_EPOCH - $CURRENT_EPOCH) / 86400 ))

        if [ $DAYS_LEFT -gt 30 ]; then
            echo -e "${GREEN}✅ SSL Certificate expires in $DAYS_LEFT days${NC}"
            log_message "INFO: SSL Certificate expires in $DAYS_LEFT days"
        elif [ $DAYS_LEFT -gt 7 ]; then
            echo -e "${YELLOW}⚠️ SSL Certificate expires in $DAYS_LEFT days (Warning)${NC}"
            log_message "WARNING: SSL Certificate expires in $DAYS_LEFT days"
        else
            echo -e "${RED}❌ SSL Certificate expires in $DAYS_LEFT days (Critical)${NC}"
            log_message "CRITICAL: SSL Certificate expires in $DAYS_LEFT days"
        fi
    else
        echo -e "${RED}❌ SSL Certificate not found${NC}"
        log_message "ERROR: SSL Certificate not found"
    fi
}

# Function to check mail queue
check_mail_queue() {
    echo -e "${BLUE}📮 Checking Mail Queue${NC}"

    QUEUE_COUNT=$(postqueue -p | tail -1 | awk '{print $5}')

    if [[ "$QUEUE_COUNT" == "empty" ]]; then
        echo -e "${GREEN}✅ Mail queue is empty${NC}"
        log_message "INFO: Mail queue is empty"
    else
        QUEUE_NUM=$(echo $QUEUE_COUNT | grep -o '[0-9]*')
        if [ $QUEUE_NUM -lt 10 ]; then
            echo -e "${YELLOW}⚠️ $QUEUE_NUM messages in queue${NC}"
            log_message "WARNING: $QUEUE_NUM messages in queue"
        else
            echo -e "${RED}❌ $QUEUE_NUM messages in queue (High)${NC}"
            log_message "CRITICAL: $QUEUE_NUM messages in queue"
        fi
    fi
}

# Function to check disk space
check_disk_space() {
    echo -e "${BLUE}💾 Checking Disk Space${NC}"

    # Check mail spool directory
    SPOOL_USAGE=$(df /var/spool/postfix | tail -1 | awk '{print $5}' | sed 's/%//')
    if [ $SPOOL_USAGE -lt 80 ]; then
        echo -e "${GREEN}✅ Mail spool disk usage: $SPOOL_USAGE%${NC}"
        log_message "INFO: Mail spool disk usage: $SPOOL_USAGE%"
    elif [ $SPOOL_USAGE -lt 90 ]; then
        echo -e "${YELLOW}⚠️ Mail spool disk usage: $SPOOL_USAGE% (Warning)${NC}"
        log_message "WARNING: Mail spool disk usage: $SPOOL_USAGE%"
    else
        echo -e "${RED}❌ Mail spool disk usage: $SPOOL_USAGE% (Critical)${NC}"
        log_message "CRITICAL: Mail spool disk usage: $SPOOL_USAGE%"
    fi

    # Check log directory
    LOG_USAGE=$(df /var/log | tail -1 | awk '{print $5}' | sed 's/%//')
    if [ $LOG_USAGE -lt 80 ]; then
        echo -e "${GREEN}✅ Log disk usage: $LOG_USAGE%${NC}"
        log_message "INFO: Log disk usage: $LOG_USAGE%"
    elif [ $LOG_USAGE -lt 90 ]; then
        echo -e "${YELLOW}⚠️ Log disk usage: $LOG_USAGE% (Warning)${NC}"
        log_message "WARNING: Log disk usage: $LOG_USAGE%"
    else
        echo -e "${RED}❌ Log disk usage: $LOG_USAGE% (Critical)${NC}"
        log_message "CRITICAL: Log disk usage: $LOG_USAGE%"
    fi
}

# Function to check recent errors
check_recent_errors() {
    echo -e "${BLUE}🚨 Checking Recent Errors (Last 1 hour)${NC}"

    # Check for authentication failures
    AUTH_FAILURES=$(grep "authentication failed" /var/log/mail.log | grep "$(date '+%b %d %H:')" | wc -l)
    if [ $AUTH_FAILURES -eq 0 ]; then
        echo -e "${GREEN}✅ No authentication failures in the last hour${NC}"
    elif [ $AUTH_FAILURES -lt 5 ]; then
        echo -e "${YELLOW}⚠️ $AUTH_FAILURES authentication failures in the last hour${NC}"
        log_message "WARNING: $AUTH_FAILURES authentication failures"
    else
        echo -e "${RED}❌ $AUTH_FAILURES authentication failures in the last hour (High)${NC}"
        log_message "CRITICAL: $AUTH_FAILURES authentication failures"
    fi

    # Check for connection errors
    CONN_ERRORS=$(grep -i "connection.*error\|timeout\|refused" /var/log/mail.log | grep "$(date '+%b %d %H:')" | wc -l)
    if [ $CONN_ERRORS -eq 0 ]; then
        echo -e "${GREEN}✅ No connection errors in the last hour${NC}"
    else
        echo -e "${YELLOW}⚠️ $CONN_ERRORS connection errors in the last hour${NC}"
        log_message "WARNING: $CONN_ERRORS connection errors"
    fi
}

# Function to check performance metrics
check_performance() {
    echo -e "${BLUE}⚡ Checking Performance Metrics${NC}"

    # CPU usage of mail processes
    POSTFIX_CPU=$(ps aux | grep postfix | grep -v grep | awk '{sum += $3} END {print sum}')
    DOVECOT_CPU=$(ps aux | grep dovecot | grep -v grep | awk '{sum += $3} END {print sum}')

    if (( $(echo "$POSTFIX_CPU < 50" | bc -l) )); then
        echo -e "${GREEN}✅ Postfix CPU usage: ${POSTFIX_CPU:-0}%${NC}"
    else
        echo -e "${RED}❌ Postfix CPU usage: ${POSTFIX_CPU:-0}% (High)${NC}"
        log_message "WARNING: High Postfix CPU usage: ${POSTFIX_CPU:-0}%"
    fi

    if (( $(echo "$DOVECOT_CPU < 50" | bc -l) )); then
        echo -e "${GREEN}✅ Dovecot CPU usage: ${DOVECOT_CPU:-0}%${NC}"
    else
        echo -e "${RED}❌ Dovecot CPU usage: ${DOVECOT_CPU:-0}% (High)${NC}"
        log_message "WARNING: High Dovecot CPU usage: ${DOVECOT_CPU:-0}%"
    fi

    # Memory usage
    TOTAL_MEM=$(free -m | awk 'NR==2{printf "%.1f", $3*100/$2}')
    if (( $(echo "$TOTAL_MEM < 80" | bc -l) )); then
        echo -e "${GREEN}✅ Memory usage: $TOTAL_MEM%${NC}"
    elif (( $(echo "$TOTAL_MEM < 90" | bc -l) )); then
        echo -e "${YELLOW}⚠️ Memory usage: $TOTAL_MEM% (Warning)${NC}"
        log_message "WARNING: High memory usage: $TOTAL_MEM%"
    else
        echo -e "${RED}❌ Memory usage: $TOTAL_MEM% (Critical)${NC}"
        log_message "CRITICAL: High memory usage: $TOTAL_MEM%"
    fi
}

# Function to test email sending
test_email_sending() {
    echo -e "${BLUE}📧 Testing Email Sending${NC}"

    TEST_EMAIL="test-$(date +%s)@vitalife.web.id"
    TEST_SUBJECT="Mail Server Test - $(date)"
    TEST_MESSAGE="This is an automated test email from mail server monitoring script."

    if echo "$TEST_MESSAGE" | mail -s "$TEST_SUBJECT" "$ADMIN_EMAIL" 2>/dev/null; then
        echo -e "${GREEN}✅ Test email sent successfully${NC}"
        log_message "INFO: Test email sent successfully to $ADMIN_EMAIL"
    else
        echo -e "${RED}❌ Failed to send test email${NC}"
        log_message "ERROR: Failed to send test email to $ADMIN_EMAIL"
    fi
}

# Function to check DNS records
check_dns() {
    echo -e "${BLUE}🌐 Checking DNS Records${NC}"

    # Check MX record
    MX_RECORD=$(dig +short MX vitalife.web.id | head -1)
    if [[ "$MX_RECORD" == *"mail.vitalife.web.id"* ]]; then
        echo -e "${GREEN}✅ MX record is correctly configured${NC}"
    else
        echo -e "${RED}❌ MX record is not configured correctly${NC}"
        log_message "ERROR: MX record is not configured correctly"
    fi

    # Check A record for mail server
    A_RECORD=$(dig +short A $MAIL_HOST)
    if [ -n "$A_RECORD" ]; then
        echo -e "${GREEN}✅ A record for $MAIL_HOST: $A_RECORD${NC}"
    else
        echo -e "${RED}❌ A record for $MAIL_HOST not found${NC}"
        log_message "ERROR: A record for $MAIL_HOST not found"
    fi
}

# Function to generate report
generate_report() {
    echo -e "\n${BLUE}📊 Mail Server Monitoring Report - $(date)${NC}"
    echo -e "${BLUE}================================================${NC}"

    check_service "postfix"
    POSTFIX_STATUS=$?

    check_service "dovecot"
    DOVECOT_STATUS=$?

    echo -e "\n${BLUE}🔌 Port Connectivity${NC}"
    check_port 25 "SMTP"
    check_port 587 "SMTP Submission"
    check_port 465 "SMTPS"
    check_port 993 "IMAPS"
    check_port 995 "POP3S"

    echo ""
    check_ssl

    echo ""
    check_mail_queue

    echo ""
    check_disk_space

    echo ""
    check_recent_errors

    echo ""
    check_performance

    echo ""
    check_dns

    echo ""
    test_email_sending

    # Overall status
    echo -e "\n${BLUE}📋 Overall Status${NC}"
    if [ $POSTFIX_STATUS -eq 0 ] && [ $DOVECOT_STATUS -eq 0 ]; then
        echo -e "${GREEN}✅ Mail server is operational${NC}"
        log_message "INFO: Mail server monitoring completed - Status: Operational"
    else
        echo -e "${RED}❌ Mail server has issues${NC}"
        log_message "ERROR: Mail server monitoring completed - Status: Issues detected"
    fi

    echo -e "\n${BLUE}📝 Log file: $LOG_FILE${NC}"
    echo -e "${BLUE}📅 Next check recommended in 1 hour${NC}"
}

# Function to setup monitoring cron job
setup_monitoring() {
    echo -e "${BLUE}⚙️ Setting up automated monitoring${NC}"

    # Create monitoring script in /usr/local/bin
    sudo cp "$0" /usr/local/bin/mail_monitor.sh
    sudo chmod +x /usr/local/bin/mail_monitor.sh

    # Add cron job for hourly monitoring
    (crontab -l 2>/dev/null; echo "0 * * * * /usr/local/bin/mail_monitor.sh >> /var/log/mail_monitor_cron.log 2>&1") | crontab -

    echo -e "${GREEN}✅ Automated monitoring setup completed${NC}"
    echo -e "${BLUE}📅 Monitoring will run every hour${NC}"
    echo -e "${BLUE}📝 Cron logs: /var/log/mail_monitor_cron.log${NC}"
}

# Function to show recent statistics
show_statistics() {
    echo -e "${BLUE}📈 Mail Server Statistics (Last 24 hours)${NC}"
    echo -e "${BLUE}============================================${NC}"

    # Sent emails
    SENT_COUNT=$(grep "status=sent" /var/log/mail.log | grep "$(date '+%b %d')" | wc -l)
    echo -e "📤 Emails sent today: $SENT_COUNT"

    # Received emails
    RECEIVED_COUNT=$(grep "status=sent.*relay=local" /var/log/mail.log | grep "$(date '+%b %d')" | wc -l)
    echo -e "📥 Emails received today: $RECEIVED_COUNT"

    # Rejected emails
    REJECTED_COUNT=$(grep "REJECT" /var/log/mail.log | grep "$(date '+%b %d')" | wc -l)
    echo -e "🚫 Emails rejected today: $REJECTED_COUNT"

    # Authentication attempts
    AUTH_ATTEMPTS=$(grep "authentication" /var/log/mail.log | grep "$(date '+%b %d')" | wc -l)
    AUTH_SUCCESS=$(grep "authentication.*successful" /var/log/mail.log | grep "$(date '+%b %d')" | wc -l)
    AUTH_FAILED=$(grep "authentication.*failed" /var/log/mail.log | grep "$(date '+%b %d')" | wc -l)

    echo -e "🔐 Authentication attempts today: $AUTH_ATTEMPTS"
    echo -e "✅ Successful authentications: $AUTH_SUCCESS"
    echo -e "❌ Failed authentications: $AUTH_FAILED"
}

# Main script
case "${1:-monitor}" in
    "monitor"|"")
        generate_report
        ;;
    "setup")
        setup_monitoring
        ;;
    "stats")
        show_statistics
        ;;
    "help")
        echo -e "${BLUE}Mail Server Monitoring Script${NC}"
        echo -e "${BLUE}=============================${NC}"
        echo "Usage: $0 [command]"
        echo ""
        echo "Commands:"
        echo "  monitor (default) - Run monitoring check"
        echo "  setup            - Setup automated monitoring"
        echo "  stats            - Show mail statistics"
        echo "  help             - Show this help"
        ;;
    *)
        echo "Unknown command: $1"
        echo "Use '$0 help' for usage information"
        exit 1
        ;;
esac
