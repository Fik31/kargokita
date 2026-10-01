<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

class CommandCenter extends Component
{
    #[Title('Live Fleet Command Center')]
    public function render()
    {
        return view('livewire.command-center');
    }
}
