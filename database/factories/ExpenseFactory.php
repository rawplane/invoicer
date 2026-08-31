<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
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
            'expense_category_id' => ExpenseCategory::factory(),
            'title' => fake()->words(3, true),
            'amount' => fake()->randomFloat(2, 50000, 5000000),
            'entry_date' => now()->subDays(fake()->numberBetween(1, 30))->toDateString(),
            'receipt_path' => null,
            'notes' => fake()->sentence(),
        ];
    }
}
