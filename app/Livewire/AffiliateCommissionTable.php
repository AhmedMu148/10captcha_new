<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Affiliate;
use App\Models\AffiliateRelation;
use App\Models\AffiliateCommission;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class AffiliateCommissionTable extends Component
{
    use WithPagination;
    public int $perPage = 10;

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        $affiliates = Affiliate::where('user_id', $user->id)->first();
        
        $affiliateCommissions = AffiliateCommission::with('affiliateRelation.user')
        ->where('aff_id', $affiliates->id)
        ->paginate($this->perPage);

        return view('livewire.affiliate-commission-table', compact('affiliates', 'affiliateCommissions'));
    }
}
