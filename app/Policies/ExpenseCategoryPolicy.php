<?php

namespace App\Policies;

use App\Models\ExpenseCategory;
use App\Models\User;

class ExpenseCategoryPolicy
{
    /**
     * Determine whether the user can view the expense category.
     */
    public function view(User $user, ExpenseCategory $expenseCategory): bool
    {
        return $user->id === $expenseCategory->user_id;
    }

    /**
     * Determine whether the user can update the expense category.
     */
    public function update(User $user, ExpenseCategory $expenseCategory): bool
    {
        return $user->id === $expenseCategory->user_id;
    }

    /**
     * Determine whether the user can delete the expense category.
     */
    public function delete(User $user, ExpenseCategory $expenseCategory): bool
    {
        return $user->id === $expenseCategory->user_id;
    }
}
