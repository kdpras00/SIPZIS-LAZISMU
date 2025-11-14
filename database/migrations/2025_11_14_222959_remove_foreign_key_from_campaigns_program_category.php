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
        $dbName = DB::connection()->getDatabaseName();

        // Remove foreign key from campaigns table
        try {
            $campaignFk = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = ?
                AND TABLE_NAME = 'campaigns' 
                AND COLUMN_NAME = 'program_category'
                AND REFERENCED_TABLE_NAME = 'programs'
                AND REFERENCED_COLUMN_NAME = 'category'
            ", [$dbName]);

            foreach ($campaignFk as $fk) {
                try {
                    DB::statement("ALTER TABLE `campaigns` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
                } catch (\Exception $e) {
                    // Continue if drop fails
                }
            }
        } catch (\Exception $e) {
            // Continue if query fails
        }

        // Remove foreign key from zakat_payments table
        try {
            $paymentFk = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = ?
                AND TABLE_NAME = 'zakat_payments' 
                AND COLUMN_NAME = 'program_category'
                AND REFERENCED_TABLE_NAME = 'programs'
                AND REFERENCED_COLUMN_NAME = 'category'
            ", [$dbName]);

            foreach ($paymentFk as $fk) {
                try {
                    DB::statement("ALTER TABLE `zakat_payments` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
                } catch (\Exception $e) {
                    // Continue if drop fails
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
        // Optionally recreate foreign keys if needed
        // But we won't do this as it causes issues with non-unique category
    }
};
