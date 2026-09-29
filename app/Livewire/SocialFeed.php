<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class SocialFeed extends Component
{
    use WithFileUploads;

    public $content = '';

    public $image;

    // Stories dummy data
    public $stories = [
        [
            'id' => 1,
            'user' => 'Budi Tejo',
            'avatar' => 'https://ui-avatars.com/api/?name=Budi+Tejo&background=random',
            'image' => 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=600',
            'time' => '2j yang lalu',
        ],
        [
            'id' => 2,
            'user' => 'PT Sinar Logistik',
            'avatar' => 'https://ui-avatars.com/api/?name=PT+Sinar&background=random',
            'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600',
            'time' => '5j yang lalu',
        ],
        [
            'id' => 3,
            'user' => 'Anto Supir',
            'avatar' => 'https://ui-avatars.com/api/?name=Anto+Supir&background=random',
            'image' => 'https://images.unsplash.com/photo-1519003722824-194d4455a60c?w=600',
            'time' => '8j yang lalu',
        ],
    ];

    public $selectedStory = null;

    public $replyText = '';

    public function viewStory($storyId)
    {
        $this->selectedStory = collect($this->stories)->firstWhere('id', $storyId);
        $this->replyText = '';
    }

    public function closeStory()
    {
        $this->selectedStory = null;
    }

    public function replyStory()
    {
        if (trim($this->replyText) === '') {
            return;
        }

        // Find the user ID of the story author based on name
        $authorName = $this->selectedStory['user'];
        $author = User::where('name', $authorName)->first();

        if ($author && $author->id !== Auth::id()) {
            Message::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $author->id,
                'message' => 'Membalas Story: '.$this->replyText,
            ]);
            session()->flash('story_message', 'Balasan berhasil dikirim.');
        }

        $this->replyText = '';
    }

    public $type = 'status';

    public function createPost()
    {
        $this->validate([
            'content' => 'required|string|max:1000',
            'type' => 'required|in:status,seeking_load,seeking_driver',
            'image' => 'nullable|image|max:5120',
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('posts', 'public');
        }

        Post::create([
            'user_id' => Auth::id(),
            'content' => $this->content,
            'type' => $this->type,
            'image' => $imagePath,
        ]);

        $this->content = '';
        $this->image = null;
        session()->flash('message', 'Postingan berhasil dibagikan.');
    }

    public function likePost($postId)
    {
        if (!Auth::user()->is_subscribed) {
            session()->flash('error', 'Fitur Like (Apresiasi) hanya tersedia untuk Member Resmi (telah deposit).');
            return;
        }

        session()->flash('message', 'Anda menyukai postingan ini.');
    }

    public function render()
    {
        $posts = Post::with('user')->latest()->get();

        $topDrivers = User::role('driver')
            ->withCount(['trips as completed_trips_count' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->orderByDesc('completed_trips_count')
            ->take(5)
            ->get();

        return view('livewire.social-feed', [
            'posts' => $posts,
            'topDrivers' => $topDrivers,
        ]);
    }
}
