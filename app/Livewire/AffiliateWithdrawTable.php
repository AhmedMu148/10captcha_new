<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AffiliateWithdraw;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class AffiliateWithdrawTable extends Component
{
    use WithPagination;

    public string $search = '';

    public int $perPage = 10;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $uid = auth()->id();
        $affiliateWithdraws = AffiliateWithdraw::query()
            ->where('user_id', $uid)
            ->when($this->search !== '', function ($query) {
                $search = '%'.$this->search.'%';

                $query->where(function ($query) use ($search) {
                    $query->where('id', 'like', $search)
                        ->orWhere('amount_5d', 'like', $search)
                        ->orWhere('method', 'like', $search);
                });
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.affiliate-withdraw-table' , compact('affiliateWithdraws'));
    }
}
