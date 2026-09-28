<?php

namespace App\Livewire;

use App\Models\VerificationRequest;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminVerification extends Component
{
    public function approve($id)
    {
        $request = VerificationRequest::findOrFail($id);
        $request->update(['status' => 'approved']);

        $user = $request->user;
        $user->assignRole($request->type);

        session()->flash('message', 'User '.$user->name.' berhasil disetujui sebagai '.$request->type.'.');
    }

    public function reject($id)
    {
        $request = VerificationRequest::findOrFail($id);
        $request->update(['status' => 'rejected']);

        session()->flash('message', 'Permintaan verifikasi ditolak.');
    }

    public function render()
    {
        $requests = VerificationRequest::with('user')->where('status', 'pending')->latest()->get();

        return view('livewire.admin-verification', [
            'requests' => $requests,
        ]);
    }
}
