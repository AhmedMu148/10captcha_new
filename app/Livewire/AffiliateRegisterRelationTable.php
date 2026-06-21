<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Affiliate;
use App\Models\AffiliateRegisterRelation;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class AffiliateRegisterRelationTable extends Component
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
        
        $affiliateRegisterRelation = AffiliateRegisterRelation::with(['user', 'report'])
        ->where('aff_id', $affiliates->id)
        ->paginate($this->perPage);

        return view('livewire.affiliate-register-relation-table', compact('affiliates', 'affiliateRegisterRelation'));
    }
}
