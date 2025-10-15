<?php
// app/View/Components/DropdownLink.php
namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DropdownLink extends Component
{
    public $href;

    public function __construct($href = null)
    {
        $this->href = $href;
    }

    public function render(): View|Closure|string
    {
        return view('components.dropdown-link');
    }
}