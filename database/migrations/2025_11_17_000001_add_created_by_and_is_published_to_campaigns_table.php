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
        Schema::table('campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('campaigns', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('status');
            }

            if (!Schema::hasColumn('campaigns', 'created_by')) {
                $table->foreignId('created_by')
                    ->nullable()
                    ->after('is_published')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('campaigns', 'created_by')) {
                $table->dropConstrainedForeignId('created_by');
            }

            if (Schema::hasColumn('campaigns', 'is_published')) {
                $table->dropColumn('is_published');
            }
        });
    }
};

