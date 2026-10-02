<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\Trip;
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
        $this->contacts = [];

        if (Auth::user()->hasRole('administrator')) {
            // Load trips for admin
            $trips = Trip::with(['driver', 'cargo.merchant'])
                ->where('status', '!=', 'completed')
                ->orWhere('updated_at', '>=', $twentyFourHoursAgo)
                ->get();

            foreach ($trips as $trip) {
                $driverId = $trip->driver_id;
                $merchantId = $trip->cargo->merchant_id;

                $lastMessage = Message::where('trip_id', $trip->id)->latest()->first();

                $this->contacts[] = [
                    'id' => 'trip_'.$trip->id,
                    'trip_id' => $trip->id,
                    'driver_id' => $driverId,
                    'merchant_id' => $merchantId,
                    'name' => 'T-'.$trip->id.': '.$trip->cargo->merchant->name.' & '.$trip->driver->name,
                    'role' => 'Order #'.$trip->load_id,
                    'avatar' => 'https://ui-avatars.com/api/?name=T+'.$trip->id.'&background=random',
                    'lastMessage' => $lastMessage ? $lastMessage->message : 'Belum ada pesan',
                    'time' => $lastMessage ? $lastMessage->created_at->diffForHumans(null, true, true) : '',
                    'unread' => 0,
                    'is_read_only' => ! $trip->admin_assistance_requested, // Admin can only chat if requested
                    'admin_assistance_requested' => $trip->admin_assistance_requested,
                    'is_admin_view' => true,
                ];
            }
        } else {
            // Get users that are part of an active deal, or a deal completed within the last 24 hours
            $users = User::where('id', '!=', $userId)
                ->where(function ($query) use ($userId, $twentyFourHoursAgo) {
                    $query->whereHas('loads.trip', function ($q) use ($userId, $twentyFourHoursAgo) {
                        $q->where('driver_id', $userId)
                            ->where(function ($subQ) use ($twentyFourHoursAgo) {
                                $subQ->where('status', '!=', 'completed')
                                    ->orWhere('updated_at', '>=', $twentyFourHoursAgo);
                            });
                    })
                        ->orWhereHas('trips', function ($q) use ($userId, $twentyFourHoursAgo) {
                            $q->whereHas('cargo', function ($subQ) use ($userId) {
                                $subQ->where('merchant_id', $userId);
                            })
                                ->where(function ($subQ) use ($twentyFourHoursAgo) {
                                    $subQ->where('status', '!=', 'completed')
                                        ->orWhere('updated_at', '>=', $twentyFourHoursAgo);
                                });
                        });
                })
                ->get();

            foreach ($users as $u) {
                $activeTrip = Trip::where(function ($q) use ($userId, $u) {
                    $q->where('driver_id', $userId)->whereHas('cargo', function ($sq) use ($u) {
                        $sq->where('merchant_id', $u->id);
                    });
                })->orWhere(function ($q) use ($userId, $u) {
                    $q->where('driver_id', $u->id)->whereHas('cargo', function ($sq) use ($userId) {
                        $sq->where('merchant_id', $userId);
                    });
                })->where('status', '!=', 'completed')->first();

                $lastMessage = Message::where(function ($q) use ($userId, $u) {
                    $q->where('sender_id', $userId)->where('receiver_id', $u->id);
                })->orWhere(function ($q) use ($userId, $u) {
                    $q->where('sender_id', $u->id)->where('receiver_id', $userId);
                })->orWhere(function ($q) use ($activeTrip) {
                    if ($activeTrip) {
                        $q->where('trip_id', $activeTrip->id);
                    } else {
                        $q->whereRaw('1=0');
                    }
                })->latest()->first();

                $unreadCount = Message::where('sender_id', $u->id)->where('receiver_id', $userId)->where('is_read', false)->count();

                $can_request_admin = false;
                if ($activeTrip && $activeTrip->cargo) {
                    $merchant = User::find($activeTrip->cargo->merchant_id);
                    if ($merchant && in_array(strtolower($merchant->tier ?? ''), ['trusted', 'premium'])) {
                        $can_request_admin = true;
                    }
                }

                $this->contacts[] = [
                    'id' => $u->id,
                    'name' => $u->name,
                    'role' => $u->roles->first()->name ?? 'User',
                    'avatar' => 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=random',
                    'lastMessage' => $lastMessage ? $lastMessage->message : 'Mulai percakapan',
                    'time' => $lastMessage ? $lastMessage->created_at->diffForHumans(null, true, true) : '',
                    'unread' => $unreadCount,
                    'is_read_only' => ! $activeTrip,
                    'admin_assistance_requested' => $activeTrip ? $activeTrip->admin_assistance_requested : false,
                    'can_request_admin' => $can_request_admin,
                    'is_admin_view' => false,
                ];
            }

            usort($this->contacts, function ($a, $b) {
                if ($a['unread'] != $b['unread']) {
                    return $b['unread'] <=> $a['unread'];
                }

                return 0;
            });
        }
    }

    public function selectContact($id)
    {
        $this->selectedContactId = $id;
        $this->loadMessages();

        if (! Auth::user()->hasRole('administrator') && ! str_starts_with((string) $id, 'trip_')) {
            Message::where('sender_id', $id)->where('receiver_id', Auth::id())->update(['is_read' => true]);
            $this->loadContacts();
        }
    }

    public function loadMessages()
    {
        if (str_starts_with((string) $this->selectedContactId, 'trip_')) {
            $contact = collect($this->contacts)->firstWhere('id', $this->selectedContactId);
            $userId1 = $contact['driver_id'];
            $userId2 = $contact['merchant_id'];

            $dbMessages = Message::where('trip_id', $contact['trip_id'])->orderBy('created_at', 'asc')->get();

            $this->activeMessages = [];
            foreach ($dbMessages as $msg) {
                $senderRole = 'admin';
                if ($msg->sender_id == $userId1) {
                    $senderRole = 'driver';
                } elseif ($msg->sender_id == $userId2) {
                    $senderRole = 'merchant';
                }

                $this->activeMessages[] = [
                    'sender' => $senderRole,
                    'text' => $msg->message,
                    'time' => $msg->created_at->format('H:i'),
                ];
            }
        } else {
            $userId = Auth::id();
            $otherId = $this->selectedContactId;

            $activeTrip = Trip::where(function ($q) use ($userId, $otherId) {
                $q->where('driver_id', $userId)->whereHas('cargo', function ($sq) use ($otherId) {
                    $sq->where('merchant_id', $otherId);
                });
            })->orWhere(function ($q) use ($userId, $otherId) {
                $q->where('driver_id', $otherId)->whereHas('cargo', function ($sq) use ($userId) {
                    $sq->where('merchant_id', $userId);
                });
            })->where('status', '!=', 'completed')->first();

            $dbMessages = Message::where(function ($q) use ($activeTrip, $userId, $otherId) {
                if ($activeTrip) {
                    $q->where('trip_id', $activeTrip->id);
                } else {
                    $q->where(function ($sq) use ($userId, $otherId) {
                        $sq->where('sender_id', $userId)->where('receiver_id', $otherId);
                    })->orWhere(function ($sq) use ($userId, $otherId) {
                        $sq->where('sender_id', $otherId)->where('receiver_id', $userId);
                    });
                }
            })->orderBy('created_at', 'asc')->get();

            $this->activeMessages = [];
            foreach ($dbMessages as $msg) {
                $senderRole = 'them';
                if ($msg->sender_id == $userId) {
                    $senderRole = 'me';
                } elseif ($msg->sender->hasRole('administrator')) {
                    $senderRole = 'admin';
                }

                $this->activeMessages[] = [
                    'sender' => $senderRole,
                    'text' => $msg->message,
                    'time' => $msg->created_at->format('H:i'),
                ];
            }
        }
    }

    public function requestAdminAssistance()
    {
        if (Auth::user()->hasRole('administrator')) {
            return;
        }

        $userId = Auth::id();
        $otherId = $this->selectedContactId;

        $activeTrip = Trip::where(function ($q) use ($userId, $otherId) {
            $q->where('driver_id', $userId)->whereHas('cargo', function ($sq) use ($otherId) {
                $sq->where('merchant_id', $otherId);
            });
        })->orWhere(function ($q) use ($userId, $otherId) {
            $q->where('driver_id', $otherId)->whereHas('cargo', function ($sq) use ($userId) {
                $sq->where('merchant_id', $userId);
            });
        })->where('status', '!=', 'completed')->first();

        if ($activeTrip && $activeTrip->cargo) {
            $merchant = User::find($activeTrip->cargo->merchant_id);
            if ($merchant && in_array(strtolower($merchant->tier ?? ''), ['trusted', 'premium'])) {
                $activeTrip->update(['admin_assistance_requested' => true]);
                $this->loadContacts();
            }
        }
    }

    public function sendMessage()
    {
        $contact = collect($this->contacts)->firstWhere('id', $this->selectedContactId);
        if (! $contact) {
            return;
        }

        if (trim($this->newMessage) === '') {
            return;
        }

        if (Auth::user()->hasRole('administrator')) {
            if (! $contact['admin_assistance_requested']) {
                return;
            }

            Message::create([
                'trip_id' => $contact['trip_id'],
                'sender_id' => Auth::id(),
                'receiver_id' => $contact['merchant_id'], // Not strictly accurate, but works
                'message' => $this->newMessage,
            ]);
        } else {
            if ($contact['is_read_only']) {
                return;
            }

            $userId = Auth::id();
            $otherId = $this->selectedContactId;

            $activeTrip = Trip::where(function ($q) use ($userId, $otherId) {
                $q->where('driver_id', $userId)->whereHas('cargo', function ($sq) use ($otherId) {
                    $sq->where('merchant_id', $otherId);
                });
            })->orWhere(function ($q) use ($userId, $otherId) {
                $q->where('driver_id', $otherId)->whereHas('cargo', function ($sq) use ($userId) {
                    $sq->where('merchant_id', $userId);
                });
            })->where('status', '!=', 'completed')->first();

            Message::create([
                'trip_id' => $activeTrip ? $activeTrip->id : null,
                'sender_id' => Auth::id(),
                'receiver_id' => $this->selectedContactId,
                'message' => $this->newMessage,
            ]);
        }

        $this->newMessage = '';
        $this->loadMessages();
        $this->loadContacts();
    }

    public function render()
    {
        return view('livewire.chat-interface');
    }
}
