<?php

namespace App\Livewire;

use App\Models\Dispute;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminDisputes extends Component
{
    public function render()
    {
        return view('livewire.admin-disputes', [
            'disputes' => Dispute::with(['trip.driver', 'cargoLoad.merchant', 'reporter'])->latest()->get(),
        ]);
    }

    public function resolveDispute($id, $notes)
    {
        $dispute = Dispute::find($id);
        if ($dispute) {
            $dispute->update([
                'status' => 'resolved',
                'resolution_notes' => $dispute->resolution_notes."\nAdmin Notes: ".$notes,
            ]);
            session()->flash('message', 'Laporan Darurat / Dispute berhasil diselesaikan.');
        }
    }
}
