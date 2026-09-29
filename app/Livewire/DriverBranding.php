<?php

namespace App\Livewire;

use App\Models\BrandingApplication;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class DriverBranding extends Component
{
    use WithFileUploads;

    public $proof_image;
    
    public function apply()
    {
        $user = Auth::user();
        
        if (!$user->is_subscribed) {
            session()->flash('error', 'Hanya Member Resmi (telah deposit) yang dapat mengajukan branding.');
            return;
        }

        $existing = BrandingApplication::where('driver_id', $user->id)
            ->whereIn('status', ['pengajuan', 'ditinjau', 'diterima', 'dicairkan'])
            ->first();

        if ($existing) {
            session()->flash('error', 'Anda sudah memiliki pengajuan branding yang sedang diproses.');
            return;
        }

        BrandingApplication::create([
            'driver_id' => $user->id,
            'status' => 'pengajuan',
            'applied_at' => now(),
            'deadline' => now()->addDays(7), // 7 days to upload proof
        ]);

        session()->flash('message', 'Pengajuan branding berhasil dikirim. Menunggu persetujuan Admin.');
    }

    public function uploadProof($id)
    {
        $this->validate([
            'proof_image' => 'required|image|max:5120',
        ]);

        $app = BrandingApplication::where('driver_id', Auth::id())->findOrFail($id);

        $path = $this->proof_image->store('branding_proofs', 'public');

        $app->update([
            'proof_image' => $path,
            'status' => 'ditinjau'
        ]);

        $this->proof_image = null;
        session()->flash('message', 'Bukti foto berhasil diunggah. Sedang ditinjau oleh Admin.');
    }

    public function render()
    {
        $applications = BrandingApplication::where('driver_id', Auth::id())->latest()->get();
        return view('livewire.driver-branding', ['applications' => $applications]);
    }
}
