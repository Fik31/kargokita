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
        $disputes = Dispute::with(['trip.driver', 'cargoLoad.merchant', 'reporter'])
            ->leftJoin('loads', 'disputes.load_id', '=', 'loads.id')
            ->orderByRaw("CASE WHEN loads.sla_type = 'Premium' THEN 1 WHEN loads.sla_type = 'Priority' THEN 2 ELSE 3 END")
            ->orderBy('disputes.created_at', 'desc')
            ->select('disputes.*')
            ->get();

        return view('livewire.admin-disputes', [
            'disputes' => $disputes,
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
