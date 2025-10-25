<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add only new indexes that don't exist yet

        // personal_access_tokens table - add missing indexes
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Add index for last_used_at (for token cleanup) - only if it doesn't exist
            if (!$this->indexExists('personal_access_tokens', 'idx_tokens_last_used_at')) {
                $table->index('last_used_at', 'idx_tokens_last_used_at');
            }

            // Add composite index for tokenable queries - only if it doesn't exist
            if (!$this->indexExists('personal_access_tokens', 'idx_tokens_tokenable_expires')) {
                $table->index(['tokenable_type', 'tokenable_id', 'expires_at'], 'idx_tokens_tokenable_expires');
            }
        });

        // bookmarks table - add missing indexes
        Schema::table('bookmarks', function (Blueprint $table) {
            // Add composite index for user bookmarks with pagination
            $table->index(['user_id', 'created_at'], 'idx_bookmarks_user_created_at');
        });

        // sms_pool_photo table - add missing indexes
        Schema::table('sms_pool_photo', function (Blueprint $table) {
            // Add index for sms_pool_id (for photo queries)
            $table->index('id_sms_pool', 'idx_sms_pool_photo_sms_pool');
        });

        // sms_pool_reaction table - add missing indexes
        Schema::table('sms_pool_reaction', function (Blueprint $table) {
            // Add composite index for reaction queries
            $table->index(['id_sms_pool', 'id_reaction'], 'idx_sms_pool_reaction_composite');
            $table->index(['id_user', 'id_sms_pool'], 'idx_sms_pool_reaction_user_post');
        });

        // user_subscription table - add missing indexes
        if (Schema::hasTable('user_subscription')) {
            Schema::table('user_subscription', function (Blueprint $table) {
                // Add composite index for user subscriptions - only if it doesn't exist
                if (!$this->indexExists('user_subscription', 'idx_user_subscription_user_subscription')) {
                    $table->index(['id_user', 'id_subscription'], 'idx_user_subscription_user_subscription');
                }
            });
        }

        // follower table - add missing indexes
        Schema::table('follower', function (Blueprint $table) {
            // Add composite index for follower relationships (using correct column names)
            $table->index(['id_user_leader', 'id_user_follower'], 'idx_follower_leader_follower');
            $table->index(['id_user_follower', 'created_at'], 'idx_follower_follower_created');
        });

        // comment table - add missing indexes
        Schema::table('comment', function (Blueprint $table) {
            // Add composite index for comment queries
            $table->index(['id_sms_pool', 'created_at'], 'idx_comment_post_created');
            $table->index(['id_user', 'created_at'], 'idx_comment_user_created');
        });

        // device table - add missing indexes
        Schema::table('device', function (Blueprint $table) {
            // Add index for user devices
            $table->index(['id_user', 'push_turn_on'], 'idx_device_user_push');
            $table->index('push_token', 'idx_device_push_token');
        });

        // email_pool table - add missing indexes
        Schema::table('email_pool', function (Blueprint $table) {
            // Add composite index for email processing
            $table->index(['status', 'created_at'], 'idx_email_pool_status_created');
            $table->index('type', 'idx_email_pool_type');
        });

        // invoice table - add missing indexes
        Schema::table('invoice', function (Blueprint $table) {
            // Add composite index for invoice queries
            $table->index(['id_user', 'created_at'], 'idx_invoice_user_created');
            $table->index(['paid_status', 'created_at'], 'idx_invoice_paid_status_created');
        });

        // staff table - add missing indexes
        Schema::table('staff', function (Blueprint $table) {
            // Add index for active staff
            $table->index(['status', 'role'], 'idx_staff_status_role');
        });

        // settings table - add missing indexes
        Schema::table('settings', function (Blueprint $table) {
            // Add index for setting names (using name instead of key)
            $table->index('name', 'idx_settings_name');
        });

        // contact table - add missing indexes
        Schema::table('contact', function (Blueprint $table) {
            // Add composite index for contact queries
            $table->index(['status', 'created_at'], 'idx_contact_status_created');
            $table->index('id_user', 'idx_contact_user');
        });

        // report_archive table - add missing indexes
        Schema::table('report_archive', function (Blueprint $table) {
            // Add composite index for report queries (using available columns)
            $table->index(['name', 'created_at'], 'idx_report_archive_name_created');
            $table->index('report_id', 'idx_report_archive_report_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop personal_access_tokens indexes
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropIndex('idx_tokens_last_used_at');
            $table->dropIndex('idx_tokens_tokenable_expires');
        });

        // Drop bookmarks indexes
        Schema::table('bookmarks', function (Blueprint $table) {
            $table->dropIndex('idx_bookmarks_user_created_at');
        });

        // Drop sms_pool_photo indexes
        Schema::table('sms_pool_photo', function (Blueprint $table) {
            $table->dropIndex('idx_sms_pool_photo_sms_pool');
        });

        // Drop sms_pool_reaction indexes
        Schema::table('sms_pool_reaction', function (Blueprint $table) {
            $table->dropIndex('idx_sms_pool_reaction_composite');
            $table->dropIndex('idx_sms_pool_reaction_user_post');
        });

        // Drop user_subscription indexes
        Schema::table('user_subscription', function (Blueprint $table) {
            $table->dropIndex('idx_user_subscription_user_subscription');
        });

        // Drop follower indexes
        Schema::table('follower', function (Blueprint $table) {
            $table->dropIndex('idx_follower_leader_follower');
            $table->dropIndex('idx_follower_follower_created');
        });

        // Drop comment indexes
        Schema::table('comment', function (Blueprint $table) {
            $table->dropIndex('idx_comment_post_created');
            $table->dropIndex('idx_comment_user_created');
        });

        // Drop device indexes
        Schema::table('device', function (Blueprint $table) {
            $table->dropIndex('idx_device_user_push');
            $table->dropIndex('idx_device_push_token');
        });

        // Drop email_pool indexes
        Schema::table('email_pool', function (Blueprint $table) {
            $table->dropIndex('idx_email_pool_status_created');
            $table->dropIndex('idx_email_pool_type');
        });

        // Drop invoice indexes
        Schema::table('invoice', function (Blueprint $table) {
            $table->dropIndex('idx_invoice_user_created');
            $table->dropIndex('idx_invoice_paid_status_created');
        });

        // Drop staff indexes
        Schema::table('staff', function (Blueprint $table) {
            $table->dropIndex('idx_staff_status_role');
        });

        // Drop settings indexes
        Schema::table('settings', function (Blueprint $table) {
            $table->dropIndex('idx_settings_name');
        });

        // Drop contact indexes
        Schema::table('contact', function (Blueprint $table) {
            $table->dropIndex('idx_contact_status_created');
            $table->dropIndex('idx_contact_user');
        });

        // Drop report_archive indexes
        Schema::table('report_archive', function (Blueprint $table) {
            $table->dropIndex('idx_report_archive_name_created');
            $table->dropIndex('idx_report_archive_report_id');
        });
    }

    /**
     * Check if an index exists on a table
     */
    private function indexExists(string $table, string $indexName): bool
    {
        try {
            if (\DB::getDriverName() === 'sqlite') {
                // SQLite syntax
                $indexes = \DB::select("PRAGMA index_list({$table})");
                foreach ($indexes as $index) {
                    if ($index->name === $indexName) {
                        return true;
                    }
                }
            } else {
                // MySQL syntax
                $indexes = \DB::select("SHOW INDEX FROM {$table}");
                foreach ($indexes as $index) {
                    if ($index->Key_name === $indexName) {
                        return true;
                    }
                }
            }
        } catch (\Exception $e) {
            // If we can't check, assume it doesn't exist
            return false;
        }
        
        return false;
    }
};
