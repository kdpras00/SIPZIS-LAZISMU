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
        // Add program_id to zakat_payments table
        Schema::table('zakat_payments', function (Blueprint $table) {
            $table->foreignId('program_id')->nullable()->after('program_category')->constrained('programs')->onDelete('set null');
        });

        // Add program_id to zakat_distributions table
        Schema::table('zakat_distributions', function (Blueprint $table) {
            $table->foreignId('program_id')->nullable()->after('program_name')->constrained('programs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zakat_payments', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropColumn('program_id');
        });

        Schema::table('zakat_distributions', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropColumn('program_id');
        });
    }
};
