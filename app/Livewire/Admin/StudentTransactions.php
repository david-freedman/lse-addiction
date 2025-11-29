<?php

namespace App\Livewire\Admin;

use App\Domains\Transaction\Models\Transaction;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

final class StudentTransactions extends Component
{
    use WithPagination;

    public int $studentId;

    public function mount(int $studentId): void
    {
        $this->studentId = $studentId;
    }

    public function render(): View
    {
        $transactions = Transaction::where('student_id', $this->studentId)
            ->with('purchasable')
            ->orderBy('created_at', 'desc')
            ->paginate(3);

        return view('livewire.admin.student-transactions', [
            'transactions' => $transactions,
        ]);
    }
}
