<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Modules\CenterExpense\Services\CenterExpenseService;

class ExpenseList extends Component
{
    use WithPagination;

    // ── Filters ───────────────────────────────────────────
    public string $dateFrom  = '';
    public string $dateTo    = '';
    public string $category  = '';

    // ── Modal ─────────────────────────────────────────────
    public bool   $showModal     = false;
    public string $title         = '';
    public float  $amount        = 0;
    public string $expenseDate   = '';
    public string $newCategory   = 'supplies';
    public string $notes         = '';

    public function mount(): void
    {
        // افتراضي: الشهر الحالي
        $this->dateFrom = now()->startOfMonth()->toDateString();
        $this->dateTo   = now()->toDateString();
        $this->expenseDate = now()->toDateString();
    }

    public function updatingDateFrom(): void { $this->resetPage(); }
    public function updatingDateTo():   void { $this->resetPage(); }
    public function updatingCategory(): void { $this->resetPage(); }

    public function openModal(): void
    {
        $this->title       = '';
        $this->amount      = 0;
        $this->expenseDate = now()->toDateString();
        $this->newCategory = 'supplies';
        $this->notes       = '';
        $this->showModal   = true;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $this->validate([
            'title'       => 'required|string|max:200',
            'amount'      => 'required|numeric|min:1',
            'expenseDate' => 'required|date',
        ], [
            'title.required'       => 'أدخلي وصف المصروف',
            'amount.required'      => 'أدخلي المبلغ',
            'amount.min'           => 'المبلغ لازم يكون أكبر من صفر',
            'expenseDate.required' => 'حددي التاريخ',
        ]);

        app(CenterExpenseService::class)->create([
            'title'        => $this->title,
            'amount'       => $this->amount,
            'expense_date' => $this->expenseDate,
            'category'     => $this->newCategory,
            'notes'        => $this->notes ?: null,
        ]);

        session()->flash('success', 'تم تسجيل المصروف ✅');
        $this->showModal = false;
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        app(CenterExpenseService::class)->delete($id);
        session()->flash('success', 'تم حذف المصروف');
    }

    public function render()
    {
        $svc      = app(CenterExpenseService::class);
        $expenses = $svc->list($this->dateFrom, $this->dateTo, $this->category ?: null);
        $total    = $svc->totalBetween($this->dateFrom, $this->dateTo);

        $categoryLabels = [
            'supplies'    => 'مواد ومستلزمات',
            'maintenance' => 'صيانة',
            'other'       => 'أخرى',
        ];

        return view('livewire.admin.expense-list',
            compact('expenses', 'total', 'categoryLabels'))
            ->layout('components.layouts.app', ['title' => 'تكاليف المركز']);
    }
}
