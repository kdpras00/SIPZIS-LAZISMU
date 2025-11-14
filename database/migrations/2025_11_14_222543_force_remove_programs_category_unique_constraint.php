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
        // Get database name
        $dbName = DB::connection()->getDatabaseName();

        // Step 1: Find and drop ALL foreign keys that reference programs.category
        try {
            $foreignKeys = DB::select("
                SELECT 
                    TABLE_NAME,
                    CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = ?
                AND REFERENCED_TABLE_NAME = 'programs'
                AND REFERENCED_COLUMN_NAME = 'category'
            ", [$dbName]);

            foreach ($foreignKeys as $fk) {
                try {
                    DB::statement("ALTER TABLE `{$fk->TABLE_NAME}` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
                } catch (\Exception $e) {
                    // Continue if drop fails
                }
            }
        } catch (\Exception $e) {
            // Continue if query fails
        }

        // Step 2: Find and drop ALL indexes on programs.category (including unique)
        try {
            $indexes = DB::select("
                SHOW INDEX FROM programs 
                WHERE Column_name = 'category'
            ");

            foreach ($indexes as $index) {
                try {
                    // Drop unique constraint
                    if ($index->Non_unique == 0) {
                        DB::statement("ALTER TABLE `programs` DROP INDEX `{$index->Key_name}`");
                    }
                } catch (\Exception $e) {
                    // Continue if drop fails
                }
            }
        } catch (\Exception $e) {
            // Continue if query fails
        }

        // Step 3: Try to drop by common constraint names
        $constraintNames = [
            'programs_category_unique',
            'programs_category_index',
            'category',
        ];

        foreach ($constraintNames as $constraintName) {
            try {
                DB::statement("ALTER TABLE `programs` DROP INDEX IF EXISTS `{$constraintName}`");
            } catch (\Exception $e) {
                // Continue if drop fails
            }
        }

        // Step 4: Create a regular (non-unique) index on category
        try {
            $existingIndex = DB::select("
                SHOW INDEX FROM programs 
                WHERE Key_name = 'programs_category_index'
            ");

            if (empty($existingIndex)) {
                Schema::table('programs', function (Blueprint $table) {
                    $table->index('category', 'programs_category_index');
                });
            }
        } catch (\Exception $e) {
            // Continue if index creation fails
        }

        // Step 5: Recreate foreign keys if they were dropped
        try {
            // Recreate foreign key for campaigns
            $campaignFkExists = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = ?
                AND TABLE_NAME = 'campaigns' 
                AND COLUMN_NAME = 'program_category'
                AND REFERENCED_TABLE_NAME = 'programs'
            ", [$dbName]);

            if (empty($campaignFkExists)) {
                try {
                    DB::statement("
                        ALTER TABLE `campaigns` 
                        ADD CONSTRAINT `fk_campaign_program_category` 
                        FOREIGN KEY (`program_category`) 
                        REFERENCES `programs` (`category`) 
                        ON DELETE SET NULL 
                        ON UPDATE RESTRICT
                    ");
                } catch (\Exception $e) {
                    // Continue if foreign key creation fails
                }
            }
        } catch (\Exception $e) {
            // Continue if query fails
        }

        try {
            // Recreate foreign key for zakat_payments
            $paymentFkExists = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = ?
                AND TABLE_NAME = 'zakat_payments' 
                AND COLUMN_NAME = 'program_category'
                AND REFERENCED_TABLE_NAME = 'programs'
            ", [$dbName]);

            if (empty($paymentFkExists)) {
                try {
                    DB::statement("
                        ALTER TABLE `zakat_payments` 
                        ADD CONSTRAINT `fk_payment_program` 
                        FOREIGN KEY (`program_category`) 
                        REFERENCES `programs` (`category`) 
                        ON DELETE SET NULL 
                        ON UPDATE RESTRICT
                    ");
                } catch (\Exception $e) {
                    // Continue if foreign key creation fails
                }
            }
        } catch (\Exception $e) {
            // Continue if query fails
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration removes constraints, so down() would add them back
        // But we don't want to add unique constraint back as it causes issues
    }
};
