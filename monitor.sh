#!/bin/bash

# Vitalife VPS Monitoring & Maintenance Script
# Run this script regularly to check system health

PROJECT_PATH="/var/www/Vitalife"
LOG_PATH="$PROJECT_PATH/storage/logs"
BACKUP_PATH="/var/backups/vitalife"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "🔍 VITALIFE VPS HEALTH CHECK"
echo "============================="

# Function to check service status
check_service() {
    if systemctl is-active --quiet $1; then
        echo -e "${GREEN}✅ $1: Running${NC}"
        return 0
    else
        echo -e "${RED}❌ $1: Not Running${NC}"
        return 1
    fi
}

# Function to check disk usage
check_disk_usage() {
    local usage=$(df / | awk 'NR==2 {print $5}' | sed 's/%//')
    if [ $usage -gt 80 ]; then
        echo -e "${RED}❌ Disk Usage: ${usage}% (High)${NC}"
        return 1
    elif [ $usage -gt 60 ]; then
        echo -e "${YELLOW}⚠️  Disk Usage: ${usage}% (Medium)${NC}"
        return 0
    else
        echo -e "${GREEN}✅ Disk Usage: ${usage}% (Good)${NC}"
        return 0
    fi
}

# Function to check memory usage
check_memory_usage() {
    local usage=$(free | grep Mem | awk '{printf "%.0f", $3/$2 * 100.0}')
    if [ $usage -gt 90 ]; then
        echo -e "${RED}❌ Memory Usage: ${usage}% (High)${NC}"
        return 1
    elif [ $usage -gt 70 ]; then
        echo -e "${YELLOW}⚠️  Memory Usage: ${usage}% (Medium)${NC}"
        return 0
    else
        echo -e "${GREEN}✅ Memory Usage: ${usage}% (Good)${NC}"
        return 0
    fi
}

# Function to check website response
check_website() {
    local response=$(curl -s -o /dev/null -w "%{http_code}" https://vitalife.web.id)
    if [ $response -eq 200 ]; then
        echo -e "${GREEN}✅ Website: Responding (HTTP $response)${NC}"
        return 0
    else
        echo -e "${RED}❌ Website: Not Responding (HTTP $response)${NC}"
        return 1
    fi
}

# Function to check database connection
check_database() {
    cd $PROJECT_PATH
    local result=$(php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'OK'; } catch(Exception \$e) { echo 'FAIL'; }" 2>/dev/null | tail -1)
    if [ "$result" = "OK" ]; then
        echo -e "${GREEN}✅ Database: Connected${NC}"
        return 0
    else
        echo -e "${RED}❌ Database: Connection Failed${NC}"
        return 1
    fi
}

# Function to check Laravel logs for errors
check_laravel_logs() {
    if [ -f "$LOG_PATH/laravel.log" ]; then
        local errors=$(tail -100 "$LOG_PATH/laravel.log" | grep -c "ERROR\|CRITICAL\|EMERGENCY")
        if [ $errors -gt 5 ]; then
            echo -e "${RED}❌ Laravel Logs: $errors recent errors found${NC}"
            echo "Recent errors:"
            tail -20 "$LOG_PATH/laravel.log" | grep "ERROR\|CRITICAL\|EMERGENCY" | tail -3
            return 1
        else
            echo -e "${GREEN}✅ Laravel Logs: No critical errors${NC}"
            return 0
        fi
    else
        echo -e "${YELLOW}⚠️  Laravel Logs: Log file not found${NC}"
        return 0
    fi
}

# Function to check queue status
check_queue_status() {
    cd $PROJECT_PATH
    local failed_jobs=$(php artisan queue:failed --format=json 2>/dev/null | jq length 2>/dev/null || echo "0")
    if [ $failed_jobs -gt 10 ]; then
        echo -e "${RED}❌ Queue: $failed_jobs failed jobs${NC}"
        return 1
    elif [ $failed_jobs -gt 0 ]; then
        echo -e "${YELLOW}⚠️  Queue: $failed_jobs failed jobs${NC}"
        return 0
    else
        echo -e "${GREEN}✅ Queue: No failed jobs${NC}"
        return 0
    fi
}

# Function to test email system
test_email_system() {
    echo "📧 Testing email system..."
    cd $PROJECT_PATH

    # Create a simple test
    local test_result=$(php -r "
        require_once 'vendor/autoload.php';
        \$app = require_once 'bootstrap/app.php';
        \$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
        \$kernel->bootstrap();

        try {
            \$emailService = new App\Services\EmailNotificationService();
            \$testUser = (object) [
                'id' => 9999,
                'name' => 'Health Check Test',
                'email' => 'admin@vitalife.web.id',
                'phone' => '+6281234567890'
            ];

            // Just test the service instantiation, don't actually send
            if (\$emailService) {
                echo 'OK';
            } else {
                echo 'FAIL';
            }
        } catch (Exception \$e) {
            echo 'FAIL';
        }
    " 2>/dev/null)

    if [ "$test_result" = "OK" ]; then
        echo -e "${GREEN}✅ Email System: Service Ready${NC}"
        return 0
    else
        echo -e "${RED}❌ Email System: Service Error${NC}"
        return 1
    fi
}

# Function to create backup
create_backup() {
    echo "💾 Creating backup..."

    # Create backup directory
    mkdir -p $BACKUP_PATH

    local date_str=$(date +%Y%m%d_%H%M%S)

    # Database backup
    echo "📊 Backing up database..."
    mysqldump -u ubay -p@Vitalife123 mainvita > "$BACKUP_PATH/database_$date_str.sql"

    # Files backup (excluding node_modules and vendor for space)
    echo "📁 Backing up files..."
    tar -czf "$BACKUP_PATH/files_$date_str.tar.gz" \
        --exclude="node_modules" \
        --exclude="vendor" \
        --exclude="storage/logs/*" \
        --exclude="storage/framework/cache/*" \
        --exclude="storage/framework/sessions/*" \
        --exclude="storage/framework/views/*" \
        $PROJECT_PATH

    # Clean old backups (keep last 7 days)
    find $BACKUP_PATH -name "*.sql" -mtime +7 -delete
    find $BACKUP_PATH -name "*.tar.gz" -mtime +7 -delete

    echo -e "${GREEN}✅ Backup completed: $BACKUP_PATH/${NC}"
}

# Function to optimize system
optimize_system() {
    echo "⚡ Optimizing system..."

    cd $PROJECT_PATH

    # Clear Laravel caches
    php artisan optimize:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    # Clear system caches
    sync && echo 3 > /proc/sys/vm/drop_caches

    # Restart services
    systemctl restart php8.2-fpm
    systemctl restart vitalife-queue

    echo -e "${GREEN}✅ System optimized${NC}"
}

# Function to show system info
show_system_info() {
    echo ""
    echo "📈 SYSTEM INFORMATION"
    echo "===================="
    echo "🖥️  Server: $(hostname)"
    echo "📅 Date: $(date)"
    echo "⏰ Uptime: $(uptime -p)"
    echo "💾 Disk: $(df -h / | awk 'NR==2 {print $3 "/" $2 " (" $5 " used)"}')"
    echo "🧠 Memory: $(free -h | awk 'NR==2{printf "%.1fG/%.1fG (%.0f%% used)", $3/1024, $2/1024, $3*100/$2}')"
    echo "🔧 PHP: $(php -v | head -n1)"
    echo "🗄️  MySQL: $(mysql --version)"
    echo ""
}

# Main execution
echo "$(date): Starting health check..."

# System checks
echo "🔍 SYSTEM HEALTH"
echo "================"
check_service "nginx"
check_service "mysql"
check_service "php8.2-fpm"
check_service "vitalife-queue"
check_disk_usage
check_memory_usage

echo ""
echo "🌐 APPLICATION HEALTH"
echo "===================="
check_website
check_database
check_laravel_logs
check_queue_status
test_email_system

# Show system info
show_system_info

# Parse command line arguments
case "${1:-status}" in
    "backup")
        create_backup
        ;;
    "optimize")
        optimize_system
        ;;
    "full")
        create_backup
        optimize_system
        ;;
    "status")
        echo "💡 Available commands:"
        echo "  ./monitor.sh status    - Show health status (default)"
        echo "  ./monitor.sh backup    - Create backup"
        echo "  ./monitor.sh optimize  - Optimize system"
        echo "  ./monitor.sh full      - Backup + optimize"
        ;;
    *)
        echo "❌ Unknown command: $1"
        echo "Available: status, backup, optimize, full"
        exit 1
        ;;
esac

echo ""
echo "✅ Health check completed at $(date)"
echo "📋 For detailed logs: tail -f $LOG_PATH/laravel.log"
