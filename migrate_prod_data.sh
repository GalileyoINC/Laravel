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
echo "📊 Total INSERT statements: $(grep -c "^INSERT INTO" "$SOURCE_FILE")"
echo ""

# Step 1: Backup current database
echo "🔧 Step 1: Backing up current Laravel database..."
backup_file="backup_before_import_$(date +%Y%m%d_%H%M%S).sql"
docker-compose exec -T mysql mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > "$backup_file"
echo "✅ Backup created: $backup_file"
echo ""

# Step 2: Import full database
echo "🚀 Step 2: Importing full production database..."
echo "⏰ This will take 30-60 minutes, please be patient..."
echo ""

# Use pv for progress if available, otherwise regular import
if command -v pv &> /dev/null; then
    echo "📊 Using pv for progress tracking..."
    pv "$SOURCE_FILE" | docker-compose exec -T mysql mysql -u $DB_USER -p$DB_PASS $DB_NAME
else
    echo "📊 Using standard import (no progress bar)..."
    docker-compose exec -T mysql mysql -u $DB_USER -p$DB_PASS $DB_NAME < "$SOURCE_FILE"
fi

if [ $? -eq 0 ]; then
    echo "✅ Database import completed successfully!"
else
    echo "❌ Database import failed!"
    exit 1
fi

echo ""
echo "🔧 Step 3: Running Laravel migrations to sync schema..."
docker-compose exec app php artisan migrate --force
echo "✅ Migrations completed"
echo ""

# Step 4: Verify import
echo "🔧 Step 4: Verifying data import..."
docker-compose exec mysql mysql -u $DB_USER -p$DB_PASS $DB_NAME -e "
SELECT 
    'users' as table_name, COUNT(*) as count FROM user
UNION ALL
SELECT 'subscriptions', COUNT(*) FROM subscription
UNION ALL
SELECT 'contacts', COUNT(*) FROM contact
UNION ALL
SELECT 'invoices', COUNT(*) FROM invoice
UNION ALL
SELECT 'conversations', COUNT(*) FROM conversation;
" 2>/dev/null || echo "⚠️  Some tables may not exist yet"

echo ""
echo "🎉 Migration completed!"
echo ""
echo "Next steps:"
echo "1. Check imported data: docker-compose exec app php artisan tinker"
echo "2. Run: docker-compose exec app php artisan optimize:clear"
echo "3. Test the application"
echo ""

