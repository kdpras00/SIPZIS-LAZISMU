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
        Schema::create('zakat_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_code')->unique(); // DNS-2024-001
            $table->foreignId('muzakki_id')->constrained('muzakki')->onDelete('cascade');
            $table->foreignId('zakat_type_id')->constrained('zakat_types')->onDelete('cascade');
            $table->decimal('wealth_amount', 15, 2)->nullable(); // Total wealth for calculation
            $table->decimal('zakat_amount', 15, 2); // Calculated zakat amount
            $table->decimal('paid_amount', 15, 2); // Actually paid amount
            $table->enum('payment_method', ['cash', 'transfer', 'check', 'online']);
            $table->string('payment_reference')->nullable(); // Transfer ref, check number, etc
            $table->date('payment_date');
            $table->integer('hijri_year')->nullable(); // Hijri year for the payment
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
            $table->string('receipt_number')->unique()->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zakat_payments');
    }
};