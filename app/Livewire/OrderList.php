<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.order-list', [
            'orders' => auth()->user()->orders()
                ->with('items.product')
                ->latest()
                ->paginate(10)
        ])->layout('components.app-layout');
    }
}