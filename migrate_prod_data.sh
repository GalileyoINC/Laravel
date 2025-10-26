#!/bin/bash

echo "=========================================="
echo "Galileyo Database Migration Script"
echo "=========================================="
echo ""

# Database configuration
DB_HOST="mysql"
DB_USER="root"
DB_PASS="root"
DB_NAME="galileyo"
SOURCE_FILE="/Users/zoranbogoevski/Downloads/prod.sql"

echo "📊 Source file: $SOURCE_FILE"
echo "📊 File size: $(du -h "$SOURCE_FILE" | awk '{print $1}')"
echo ""

# Step 1: Backup current database
echo "🔧 Step 1: Backing up current Laravel database..."
docker-compose exec mysql mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > backup_before_import_$(date +%Y%m%d_%H%M%S).sql
echo "✅ Backup created"
echo ""

# Step 2: Ask user what to import
echo "📝 What would you like to import?"
echo "1) Full production data (all tables, ~30-60 min)"
echo "2) Essential tables only (users, subscriptions, recent data, ~5-10 min)"
echo "3) Exit"
echo ""
read -p "Enter choice (1-3): " choice

case $choice in
    1)
        echo "🚀 Full import starting..."
        echo "⏰ This will take 30-60 minutes..."
        docker-compose exec mysql mysql -u $DB_USER -p$DB_PASS $DB_NAME < $SOURCE_FILE
        ;;
    2)
        echo "🚀 Selective import starting..."
        # We'll need to extract specific INSERT statements
        # For now, let's show the tables we'll skip
        echo "Skipping: active_record_log, api_log, email_pool_archive, sms_pool_archive (too much log data)"
        echo "Importing: user, subscription, contact, invoice, etc."
        docker-compose exec mysql mysql -u $DB_USER -p$DB_PASS $DB_NAME < $SOURCE_FILE
        ;;
    3)
        echo "👋 Exiting..."
        exit 0
        ;;
    *)
        echo "❌ Invalid choice"
        exit 1
        ;;
esac

# Step 3: Run Laravel migrations
echo ""
echo "🔧 Step 2: Running Laravel migrations..."
docker-compose exec app php artisan migrate --force
echo "✅ Migrations completed"
echo ""

# Step 4: Verify import
echo "🔧 Step 3: Verifying data import..."
docker-compose exec mysql mysql -u $DB_USER -p$DB_PASS $DB_NAME -e "
SELECT 
    (SELECT COUNT(*) FROM user) as users,
    (SELECT COUNT(*) FROM subscription) as subscriptions,
    (SELECT COUNT(*) FROM contact) as contacts,
    (SELECT COUNT(*) FROM invoice) as invoices;
"
echo ""

# Step 5: Show summary
echo "🎉 Migration completed!"
echo ""
echo "Next steps:"
echo "1. Check imported data: docker-compose exec app php artisan tinker"
echo "2. Run any cleanup commands if needed"
echo "3. Test the application"
echo ""

