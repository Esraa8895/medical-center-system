<?php

namespace App\Modules\CenterExpense\Services;

use App\Modules\CenterExpense\Models\CenterExpense;

class CenterExpenseService
{
    public function list(
        ?string $from,
        ?string $to,
        ?string $category
    ) {
        return CenterExpense::query()
            ->when($from,     fn($q) => $q->whereDate('expense_date', '>=', $from))
            ->when($to,       fn($q) => $q->whereDate('expense_date', '<=', $to))
            ->when($category, fn($q) => $q->where('category', $category))
            ->orderByDesc('expense_date')
            ->get();
    }

    public function totalBetween(?string $from, ?string $to): float
    {
        return (float) CenterExpense::query()
            ->when($from, fn($q) => $q->whereDate('expense_date', '>=', $from))
            ->when($to,   fn($q) => $q->whereDate('expense_date', '<=', $to))
            ->sum('amount');
    }

    public function create(array $data): CenterExpense
    {
        $data['created_by'] = auth()->id();
        return CenterExpense::create($data);
    }

    public function delete(int $id): void
    {
        CenterExpense::findOrFail($id)->delete();
    }
}
