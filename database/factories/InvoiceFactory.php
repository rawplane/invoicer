<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 100000, 10000000);
        $tax = $subtotal * 0.11;
        $discount = 0;
        $total = $subtotal + $tax - $discount;

        return [
            'user_id' => User::factory(),
            'client_id' => Client::factory(),
            'invoice_number' => 'INV-'.date('Y').'-'.fake()->unique()->numberBetween(1000, 9999),
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(14)->toDateString(),
            'status' => fake()->randomElement(['draft', 'sent', 'paid', 'overdue', 'cancelled']),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
            'notes' => fake()->sentence(),
        ];
    }
}
