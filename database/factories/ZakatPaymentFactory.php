<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Muzakki;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ZakatPayment>
 */
class ZakatPaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_code' => 'DNS-' . date('Y') . '-' . fake()->unique()->numberBetween(100, 999),
            'muzakki_id' => Muzakki::factory(),
            'zakat_amount' => fake()->randomElement([50000, 100000, 150000, 200000, 250000]),
            'paid_amount' => fake()->randomElement([50000, 100000, 150000, 200000, 250000]),
            'payment_method' => fake()->randomElement(['cash', 'transfer', 'check', 'online']),
            'payment_date' => fake()->date(),
            'status' => fake()->randomElement(['pending', 'completed', 'cancelled']),
            'receipt_number' => 'RCP-' . date('Ym') . '-' . fake()->unique()->numberBetween(1000, 9999),
        ];
    }
}
