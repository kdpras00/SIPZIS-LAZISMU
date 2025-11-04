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
        // First, ensure programs.category has an index (required for foreign key)
        $programsIndexes = collect(DB::select("
            SELECT INDEX_NAME 
            FROM information_schema.STATISTICS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'programs' 
            AND COLUMN_NAME = 'category'
        "))->pluck('INDEX_NAME')->toArray();

        // Add index if it doesn't exist
        if (empty($programsIndexes)) {
            try {
                Schema::table('programs', function (Blueprint $table) {
                    $table->index('category', 'programs_category_index');
                });
            } catch (\Exception $e) {
                // Index might already exist or table might not exist
            }
        }

        // Clean up any invalid references
        try {
            DB::statement("
                UPDATE campaigns 
                SET program_category = NULL 
                WHERE program_category NOT IN (SELECT category FROM programs WHERE category IS NOT NULL)
            ");
        } catch (\Exception $e) {
            // Continue if update fails
        }

        // Check if foreign key already exists
        $existingForeignKeys = collect(DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'campaigns' 
            AND COLUMN_NAME = 'program_category'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        "))->pluck('CONSTRAINT_NAME')->toArray();

        // Only attempt to create foreign key if it doesn't exist
        if (!in_array('fk_campaign_program_category', $existingForeignKeys)) {
            try {
                // Get column details to ensure compatibility
                $campaignColumn = DB::select("
                    SELECT COLUMN_TYPE, CHARACTER_SET_NAME, COLLATION_NAME
                    FROM information_schema.COLUMNS 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'campaigns' 
                    AND COLUMN_NAME = 'program_category'
                ")[0] ?? null;

                $programColumn = DB::select("
                    SELECT COLUMN_TYPE, CHARACTER_SET_NAME, COLLATION_NAME
                    FROM information_schema.COLUMNS 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'programs' 
                    AND COLUMN_NAME = 'category'
                ")[0] ?? null;

                // Only create foreign key if both columns exist and have compatible types
                if ($campaignColumn && $programColumn) {
                    // Normalize VARCHAR lengths for comparison (VARCHAR(255) vs VARCHAR(191) should be compatible)
                    $campaignType = preg_replace('/varchar\(\d+\)/i', 'varchar', $campaignColumn->COLUMN_TYPE);
                    $programType = preg_replace('/varchar\(\d+\)/i', 'varchar', $programColumn->COLUMN_TYPE);

                    // Try to create foreign key using raw SQL for better control
                    if (strtolower($campaignType) === strtolower($programType)) {
                        DB::statement("
                            ALTER TABLE `campaigns` 
                            ADD CONSTRAINT `fk_campaign_program_category` 
                            FOREIGN KEY (`program_category`) 
                            REFERENCES `programs` (`category`) 
                            ON DELETE SET NULL 
                            ON UPDATE RESTRICT
                        ");
                    }
                }
            } catch (\Exception $e) {
                // Log error but don't fail migration
                // Foreign key constraint is optional - application logic can handle referential integrity
                \Log::warning('Failed to create foreign key fk_campaign_program_category: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            // Check if the foreign key constraint exists before dropping
            $existingForeignKeys = collect(DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'campaigns' 
                AND COLUMN_NAME = 'program_category'
                AND REFERENCED_TABLE_NAME IS NOT NULL
                AND CONSTRAINT_NAME = 'fk_campaign_program_category'
            "))->pluck('CONSTRAINT_NAME')->toArray();

            if (in_array('fk_campaign_program_category', $existingForeignKeys)) {
                $table->dropForeign('fk_campaign_program_category');
            }
        });
    }
};
