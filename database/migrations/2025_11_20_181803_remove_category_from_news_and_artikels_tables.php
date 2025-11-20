<?php

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
        // Remove category column from news table
        Schema::table('news', function (Blueprint $table) {
            // Drop index first if it exists
            $table->dropIndex(['category', 'is_published']);
            // Drop the category column
            $table->dropColumn('category');
        });

        // Remove category column from artikels table
        Schema::table('artikels', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add category column back to news table
        Schema::table('news', function (Blueprint $table) {
            $table->string('category')->default('zakat')->after('content');
            $table->index(['category', 'is_published']);
        });

        // Add category column back to artikels table
        Schema::table('artikels', function (Blueprint $table) {
            $table->string('category')->default('zakat')->after('content');
        });
    }
};
