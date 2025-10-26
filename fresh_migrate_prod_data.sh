#!/bin/bash

echo "=========================================="
echo "Fresh Production Database Migration"
echo "=========================================="
echo ""

# Database configuration
DB_HOST="mysql"
DB_USER="root"
DB_PASS="root"
DB_NAME="galileyo"
SOURCE_FILE="/Users/zoranbogoevski/Downloads/prod.sql"

echo "⚠️  WARNING: This will DROP and recreate the database!"
echo "📊 Source file: $SOURCE_FILE"
echo "📊 File size: $(du -h "$SOURCE_FILE" | awk '{print $1}')"
echo "📊 Total INSERT statements: $(grep -c "^INSERT INTO" "$SOURCE_FILE")"
echo ""
read -p "⚠️  Are you sure you want to continue? (yes/no): " confirm

if [ "$confirm" != "yes" ]; then
    echo "❌ Migration cancelled"
    exit 1
fi

# Step 1: Run Laravel migrate:fresh to drop and recreate database
echo ""
echo "🗑️  Step 1: Running migrate:fresh (drops all tables and re-runs migrations)..."
docker-compose exec app php artisan migrate:fresh --force
echo "✅ Database recreated with fresh Laravel schema"
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
echo "🔧 Step 3: Verifying data import..."

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
SELECT 'conversations', COUNT(*) FROM conversation
UNION ALL
SELECT 'invoice_lines', COUNT(*) FROM invoice_line
UNION ALL
SELECT 'money_transactions', COUNT(*) FROM money_transaction;
" 2>/dev/null || echo "⚠️  Some tables may not exist yet"

echo ""
echo "🔧 Step 5: Clearing cache..."
docker-compose exec app php artisan optimize:clear
echo "✅ Cache cleared"
echo ""

echo "🎉 Migration completed!"
echo ""
echo "Next steps:"
echo "1. Test the application in the browser"
echo "2. Check data: docker-compose exec app php artisan tinker"
echo "3. Test API endpoints"
echo ""

