# Database Migration Guide: Yii to Laravel

## Current Situation

- **Yii Production DB**: `/Users/zoranbogoevski/Downloads/prod.sql` (9.0 GB)
- **Laravel Local DB**: `galileyo` (Docker MySQL)

## Migration Strategy

### Option 1: Full Production Data Import (Recommended for Testing)

Import all production data to Laravel database.

**Pros:**
- Complete data set
- Real user data for testing
- Historical data preserved

**Cons:**
- Large file (9 GB)
- Takes 30-60+ minutes
- May have Yii-specific data that needs cleanup

**Steps:**

```bash
# 1. Backup Laravel database first
docker-compose exec mysql mysqldump -u root -proot galileyo > backup_laravel_before_import.sql

# 2. Import production SQL
docker-compose exec mysql mysql -u root -proot galileyo < /Users/zoranbogoevski/Downloads/prod.sql

# 3. Run Laravel migrations to ensure schema is up to date
docker-compose exec app php artisan migrate --force

# 4. Verify data import
docker-compose exec mysql mysql -u root -proot galileyo -e "SELECT COUNT(*) as user_count FROM user;"
```

### Option 2: Selective Data Import (Recommended for Development)

Import only essential tables and recent data.

**Advantages:**
- Faster (5-10 minutes)
- Smaller database
- Easier to work with
- Avoids old/invalid data

**Steps:**

```bash
# 1. Extract and import only essential tables
docker-compose exec mysql mysql -u root -proot galileyo << EOF
SOURCE /Users/zoranbogoevski/Downloads/prod.sql
EOF
```

**Essential Tables to Import:**
- `user` - All users (but maybe limit to last 1000)
- `subscription` - Active subscriptions
- `user_subscription` / `user_satellite_subscription` - User subscriptions
- `invoice` - Recent invoices (last 6 months)
- `contract_line` - Financial contracts
- `credit_card` - Payment cards (last 6 months)
- `contact` - Contact messages (last 3 months)
- `news` - Recent news/articles
- `conversation` / `conversation_message` - Chat messages
- Settings, configurations

**SQL to extract specific tables:**

```bash
# Extract only specific tables
mysqldump -u your_user -p your_database_name \
  --tables user subscription user_satellite_subscription invoice \
  --where="created_at > '2024-01-01'" \
  > selective_migration.sql
```

### Option 3: Table-by-Table Migration Script

Create a script to import tables one by one.

**Steps:**

```bash
# Create migration script
cat > import_prod_data.sh << 'EOF'
#!/bin/bash

DB_HOST="mysql"
DB_USER="root"
DB_PASS="root"
DB_NAME="galileyo"
SOURCE_FILE="/Users/zoranbogoevski/Downloads/prod.sql"

# Import one table at a time
grep "INSERT INTO \`user\`" $SOURCE_FILE | docker-compose exec -T mysql mysql -u $DB_USER -p$DB_PASS $DB_NAME

# Repeat for other tables...
EOF

chmod +x import_prod_data.sh
./import_prod_data.sh
```

## Column Name Mapping

Need to handle column name differences:

### User Table
| Yii Column | Laravel Column | Notes |
|------------|---------------|-------|
| `id_user` | `id` | Primary key |
| `password_hash` | `password` | Password field |
| `auth_key` | Keep | Authentication key |
| `created_at` | `created_at` | Timestamp |
| `updated_at` | `updated_at` | Timestamp |

### Other Tables
- Check for `id_` prefix (user_id in Laravel vs id_user in Yii)
- Timestamps might be different formats
- JSON fields in Laravel vs TEXT fields in Yii

## Data Cleanup Needed

After importing, run these commands:

```bash
# 1. Fix password hashes (if needed)
# Yii uses different hash format than Laravel
# Some users might need to reset passwords

# 2. Clean up invalid records
docker-compose exec app php artisan tinker
# Delete test users, inactive users, etc.

# 3. Update timestamps
# Some Yii fields use different date formats

# 4. Fix relationships
# Ensure all foreign keys are correct

# 5. Run factories for missing relationships
docker-compose exec app php artisan db:seed --class=MissingDataSeeder
```

## Recommended Approach

**For Development:**
1. Use **Option 2** (Selective Import)
2. Import last 3-6 months of data
3. Use factories to fill in missing relationships
4. Test with real user scenarios

**For Production:**
1. Use **Option 1** (Full Import)
2. Plan downtime for migration
3. Backup everything first
4. Test thoroughly before switching

## Next Steps

1. Choose migration option
2. Backup Laravel database
3. Import production data
4. Run Laravel migrations
5. Clean up data
6. Test application
7. Verify all relationships work

## Verification Commands

```bash
# Check imported data
docker-compose exec mysql mysql -u root -proot galileyo -e "
SELECT COUNT(*) as total_users FROM user;
SELECT COUNT(*) as total_subscriptions FROM subscription;
SELECT COUNT(*) as recent_contacts FROM contact WHERE created_at > '2024-10-01';
"

# Test Laravel models
docker-compose exec app php artisan tinker
> User::count()
> Subscription::count()
> Contact::count()
```

## Common Issues & Solutions

### Issue 1: Foreign Key Violations
**Solution:** Import tables in correct order (users first, then subscriptions)

### Issue 2: Date Format Mismatch
**Solution:** Convert Yii dates to Laravel format

### Issue 3: Missing Columns
**Solution:** Run migrations first, then import data

### Issue 4: Duplicate Keys
**Solution:** Skip existing records or truncate tables before import

## Time Estimates

- **Full Import**: 30-60 minutes
- **Selective Import**: 5-10 minutes
- **Cleanup**: 10-20 minutes
- **Verification**: 5-10 minutes

**Total Time**: 20-90 minutes depending on approach

---

**Ready to proceed with which option?**

