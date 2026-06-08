<?php

namespace App\Livewire;

use App\Models\Report;
use App\Models\ReportDaily;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;


#[Layout('layouts.app')]
class ReportTable extends Component
{
    use WithPagination;
    public $search = '';
    
    public function render()
    {
        $user = auth()->id();

        $todayReports = ReportDaily::where('user_id', $user)
            ->whereDate('created_at', Carbon::today())
            ->when($this->search, function ($query) {
                $query->where('id', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(5, ['*'], 'todayReports');

        $allReports = Report::where('user_id', $user)
            ->when($this->search, function ($query) {
                $query->where('id', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(5, ['*'], 'allReports');

        return view('livewire.report-table', compact(
            'todayReports',
            'allReports'
        ));
    }
}
