<?php

namespace App\Livewire;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;


class PaymentsHistoryTable extends Component
{
    use WithPagination;

    public function render()
    {
        $user = request()->user();
        $filter = request()->query('status', 'all');

        $query = Payment::where('user_id', $user->id)->latest();

        if ($filter === 'completed') {
            $query->where('status', Payment::STATUS_COMPLETED);
        } elseif ($filter === 'uncompleted') {
            $query->where('status', Payment::STATUS_UNCOMPLETED);
        } elseif ($filter === 'canceled') {
            $query->where('status', Payment::STATUS_CANCELED);
        }

        $payments = $query->paginate(10);

        return view('livewire.payments-history-table', compact('user', 'payments', 'filter'));
    }
}
