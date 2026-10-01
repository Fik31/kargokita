<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

class Cargo3dVisualizer extends Component
{
    #[Title('LTL Cargo 3D Visualizer')]
    public function render()
    {
        return view('livewire.cargo3d-visualizer');
    }
}
