<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Drop foreign keys that reference programs.category
        try {
            // Drop foreign key from campaigns table
            $campaignFk = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'campaigns' 
                AND COLUMN_NAME = 'program_category'
                AND REFERENCED_TABLE_NAME = 'programs'
                AND REFERENCED_COLUMN_NAME = 'category'
            ");
            if (!empty($campaignFk)) {
                $constraintName = $campaignFk[0]->CONSTRAINT_NAME;
                DB::statement("ALTER TABLE `campaigns` DROP FOREIGN KEY `{$constraintName}`");
            }
        } catch (\Exception $e) {
            // Continue if foreign key doesn't exist
        }

        try {
            // Drop foreign key from zakat_payments table
            $paymentFk = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'zakat_payments' 
                AND COLUMN_NAME = 'program_category'
                AND REFERENCED_TABLE_NAME = 'programs'
                AND REFERENCED_COLUMN_NAME = 'category'
            ");
            if (!empty($paymentFk)) {
                $constraintName = $paymentFk[0]->CONSTRAINT_NAME;
                DB::statement("ALTER TABLE `zakat_payments` DROP FOREIGN KEY `{$constraintName}`");
            }
        } catch (\Exception $e) {
            // Continue if foreign key doesn't exist
        }

        // Step 2: Drop unique constraint on category using raw SQL
        // Must use raw SQL because Schema builder might fail if foreign keys still reference it
        try {
            DB::statement("ALTER TABLE `programs` DROP INDEX `programs_category_unique`");
        } catch (\Exception $e) {
            // Index might not exist, continue anyway
        }

        // Step 3: Create a regular (non-unique) index on category (required for foreign keys)
        Schema::table('programs', function (Blueprint $table) {
            try {
                $indexes = DB::select("SHOW INDEX FROM programs WHERE Key_name = 'programs_category_index'");
                if (empty($indexes)) {
                    $table->index('category', 'programs_category_index');
                }
            } catch (\Exception $e) {
                // Index might already exist
            }
        });

        // Step 4: Recreate foreign keys
        try {
            // Recreate foreign key for campaigns
            $campaignFkExists = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'campaigns' 
                AND COLUMN_NAME = 'program_category'
                AND REFERENCED_TABLE_NAME = 'programs'
            ");
            if (empty($campaignFkExists)) {
                DB::statement("
                    ALTER TABLE `campaigns` 
                    ADD CONSTRAINT `fk_campaign_program_category` 
                    FOREIGN KEY (`program_category`) 
                    REFERENCES `programs` (`category`) 
                    ON DELETE SET NULL 
                    ON UPDATE RESTRICT
                ");
            }
        } catch (\Exception $e) {
            // Continue if foreign key creation fails
        }

        try {
            // Recreate foreign key for zakat_payments
            $paymentFkExists = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'zakat_payments' 
                AND COLUMN_NAME = 'program_category'
                AND REFERENCED_TABLE_NAME = 'programs'
            ");
            if (empty($paymentFkExists)) {
                DB::statement("
                    ALTER TABLE `zakat_payments` 
                    ADD CONSTRAINT `fk_payment_program` 
                    FOREIGN KEY (`program_category`) 
                    REFERENCES `programs` (`category`) 
                    ON DELETE SET NULL 
                    ON UPDATE RESTRICT
                ");
            }
        } catch (\Exception $e) {
            // Continue if foreign key creation fails
        }

        // Step 5: Remove unique constraint on slug
        Schema::table('programs', function (Blueprint $table) {
            try {
                $indexes = DB::select("SHOW INDEX FROM programs WHERE Key_name LIKE '%slug%' AND Non_unique = 0");
                if (!empty($indexes)) {
                    foreach ($indexes as $index) {
                        $table->dropUnique($index->Key_name);
                    }
                }
            } catch (\Exception $e) {
                // Continue if checking fails
            }
        });

        // Use raw SQL as fallback for slug
        try {
            DB::statement("ALTER TABLE programs DROP INDEX IF EXISTS programs_slug_unique");
        } catch (\Exception $e) {
            // Continue if it doesn't exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            // Add back unique constraints if needed
            // Note: This might fail if there are duplicate values
            try {
                $indexes = DB::select("SHOW INDEX FROM programs WHERE Key_name = 'programs_category_unique'");
                if (empty($indexes)) {
                    $table->unique('category', 'programs_category_unique');
                }
            } catch (\Exception $e) {
                // Continue if adding fails
            }

            try {
                $indexes = DB::select("SHOW INDEX FROM programs WHERE Key_name LIKE '%slug%' AND Non_unique = 0");
                if (empty($indexes)) {
                    $table->unique('slug', 'programs_slug_unique');
                }
            } catch (\Exception $e) {
                // Continue if adding fails
            }
        });
    }
};
