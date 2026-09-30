<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileGallery extends Component
{
    use WithFileUploads;

    public $photo;
    public $caption = '';

    public function save()
    {
        $this->validate([
            'photo' => 'image|max:5120', // 5MB Max
            'caption' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        
        if ($user->galleries()->count() >= 6) {
            session()->flash('error', 'Maksimal 6 foto diperbolehkan.');
            return;
        }

        $path = $this->photo->store('galleries', 'public');

        $user->galleries()->create([
            'image_path' => $path,
            'caption' => $this->caption,
        ]);

        $this->reset(['photo', 'caption']);
        session()->flash('message', 'Foto berhasil diunggah.');
    }

    public function delete($id)
    {
        $gallery = Auth::user()->galleries()->find($id);
        
        if ($gallery) {
            Storage::disk('public')->delete($gallery->image_path);
            $gallery->delete();
            session()->flash('message', 'Foto berhasil dihapus.');
        }
    }

    public function render()
    {
        return view('livewire.profile-gallery', [
            'galleries' => Auth::user()->galleries()->latest()->get(),
        ]);
    }
}
