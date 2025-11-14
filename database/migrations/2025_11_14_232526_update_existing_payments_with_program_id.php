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
        // Update existing payments to have program_id based on program_category and slug matching
        // First, try to match by program_category and program slug from URL or program_name
        DB::statement("
            UPDATE zakat_payments zp
            INNER JOIN programs p ON (
                zp.program_category = p.category 
                OR zp.program_category = p.slug
            )
            SET zp.program_id = p.id
            WHERE zp.program_id IS NULL
            AND zp.program_category IS NOT NULL
            AND zp.program_category != ''
        ");

        // Also update distributions
        DB::statement("
            UPDATE zakat_distributions zd
            INNER JOIN programs p ON (
                zd.program_name = p.name
                OR zd.program_name = p.slug
                OR zd.program_name = p.category
            )
            SET zd.program_id = p.id
            WHERE zd.program_id IS NULL
            AND zd.program_name IS NOT NULL
            AND zd.program_name != ''
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible as we're updating existing data
        // If needed, you can set program_id to NULL, but this is not recommended
    }
};
