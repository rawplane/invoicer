<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'invoice_id' => Invoice::factory(),
            'amount' => fake()->randomFloat(2, 100000, 5000000),
            'payment_date' => now()->toDateString(),
            'payment_method' => fake()->randomElement(['transfer', 'cash', 'qris', 'credit_card']),
            'notes' => fake()->sentence(),
        ];
    }
}
