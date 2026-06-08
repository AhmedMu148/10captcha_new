<?php

namespace App\Livewire;

use App\Models\CustomImage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CustomImagesTable extends Component
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
        $customImages = CustomImage::query()
            ->when($this->search !== '', function ($query) {
                $search = '%'.$this->search.'%';

                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', $search)
                        ->orWhere('name', 'like', $search)
                        ->orWhere('description', 'like', $search);
                });
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.custom-images-table', [
            'customImages' => $customImages,
        ]);
    }
}
