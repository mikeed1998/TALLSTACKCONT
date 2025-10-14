<?php

namespace App\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public $count = 0;
    public $name = '';
    public $items = [];

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        if ($this->count > 0) {
            $this->count--;
        }
    }

    public function addItem()
    {
        $this->validate([
            'name' => 'required|min:2|max:50'
        ]);

        if ($this->name) {
            $this->items[] = $this->name;
            $this->name = '';
        }
    }

    public function removeItem($index)
    {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
            $this->items = array_values($this->items); // Reindexar array
        }
    }

    public function render()
    {
        return view('livewire.counter');
    }
}