<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ChatInterface extends Component
{
    public $contacts = [];

    public $selectedContactId = null;

    public $activeMessages = [];

    public $newMessage = '';

    public function mount()
    {
        $this->loadContacts();
    }

    public function loadContacts()
    {
        $userId = Auth::id();

        $twentyFourHoursAgo = now()->subHours(24);

        // Get users that are part of an active deal, or a deal completed within the last 24 hours
        $users = User::where('id', '!=', $userId)
            ->where(function($query) use ($userId, $twentyFourHoursAgo) {
                // If the other user is a merchant, check if the current user (driver) has a trip with their load
                $query->whereHas('loads.trip', function($q) use ($userId, $twentyFourHoursAgo) {
                    $q->where('driver_id', $userId)
                      ->where(function($subQ) use ($twentyFourHoursAgo) {
                          $subQ->where('status', '!=', 'completed')
                               ->orWhere('updated_at', '>=', $twentyFourHoursAgo);
                      });
                })
                // OR If the other user is a driver, check if they have a trip with the current user's (merchant) load
                ->orWhereHas('trips', function($q) use ($userId, $twentyFourHoursAgo) {
                    $q->whereHas('cargo', function($subQ) use ($userId) {
                        $subQ->where('merchant_id', $userId);
                    })
                    ->where(function($subQ) use ($twentyFourHoursAgo) {
                          $subQ->where('status', '!=', 'completed')
                               ->orWhere('updated_at', '>=', $twentyFourHoursAgo);
                    });
                });
            })
            ->get();

        $this->contacts = [];
        foreach ($users as $u) {
            $lastMessage = Message::where(function ($q) use ($userId, $u) {
                $q->where('sender_id', $userId)->where('receiver_id', $u->id);
            })->orWhere(function ($q) use ($userId, $u) {
                $q->where('sender_id', $u->id)->where('receiver_id', $userId);
            })->latest()->first();

            $unreadCount = Message::where('sender_id', $u->id)->where('receiver_id', $userId)->where('is_read', false)->count();

            $this->contacts[] = [
                'id' => $u->id,
                'name' => $u->name,
                'role' => $u->roles->first()->name ?? 'User',
                'avatar' => 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=random',
                'lastMessage' => $lastMessage ? $lastMessage->message : 'Mulai percakapan',
                'time' => $lastMessage ? $lastMessage->created_at->diffForHumans(null, true, true) : '',
                'unread' => $unreadCount,
            ];
        }

        // Sort by unread and time
        usort($this->contacts, function ($a, $b) {
            if ($a['unread'] != $b['unread']) {
                return $b['unread'] <=> $a['unread'];
            }

            return 0; // Simplified for now
        });
    }

    public function selectContact($id)
    {
        $this->selectedContactId = $id;
        $this->loadMessages();

        // Mark as read
        Message::where('sender_id', $id)->where('receiver_id', Auth::id())->update(['is_read' => true]);
        $this->loadContacts(); // Refresh unread count
    }

    public function loadMessages()
    {
        $userId = Auth::id();
        $otherId = $this->selectedContactId;

        $dbMessages = Message::where(function ($q) use ($userId, $otherId) {
            $q->where('sender_id', $userId)->where('receiver_id', $otherId);
        })->orWhere(function ($q) use ($userId, $otherId) {
            $q->where('sender_id', $otherId)->where('receiver_id', $userId);
        })->orderBy('created_at', 'asc')->get();

        $this->activeMessages = [];
        foreach ($dbMessages as $msg) {
            $this->activeMessages[] = [
                'sender' => $msg->sender_id == $userId ? 'me' : 'them',
                'text' => $msg->message,
                'time' => $msg->created_at->format('H:i'),
            ];
        }
    }

    public function sendMessage()
    {
        if (trim($this->newMessage) === '' || ! $this->selectedContactId) {
            return;
        }

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedContactId,
            'message' => $this->newMessage,
        ]);

        $this->newMessage = '';
        $this->loadMessages();
        $this->loadContacts();
    }

    public function render()
    {
        return view('livewire.chat-interface');
    }
}
