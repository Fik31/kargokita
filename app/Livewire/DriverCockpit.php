<?php

namespace App\Livewire;

use App\Models\Trip;
use App\Models\TripEvent;
use App\Models\TripPhoto;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class DriverCockpit extends Component
{
    use WithFileUploads;

    public $photo;

    public $activeTrip;

    // Report properties
    public $showStopForm = false;

    public $showDeviationForm = false;

    public $stopReason = '';

    public $stopDuration = '';

    public $deviationReason = '';

    public function mount()
    {
        // 1. Cek trip yang sedang berjalan
        $this->activeTrip = Trip::with(['cargo.merchant', 'photos'])->where('driver_id', Auth::id())
            ->whereIn('status', ['loading', 'in_transit'])
            ->latest()
            ->first();

        // 2. Jika tidak ada trip berjalan, cek apakah ada bid yang diterima dan belum dibuatkan trip
        if (! $this->activeTrip) {
            $acceptedBids = Auth::user()->bids()->where('status', 'accepted')->whereHas('cargo', function ($q) {
                $q->where('status', 'in_transit');
            })->get();

            $newBidToProcess = null;
            foreach ($acceptedBids as $bid) {
                if (! Trip::where('load_id', $bid->load_id)->where('driver_id', Auth::id())->exists()) {
                    $newBidToProcess = $bid;
                    break;
                }
            }

            if ($newBidToProcess) {
                $this->activeTrip = Trip::create([
                    'load_id' => $newBidToProcess->load_id,
                    'driver_id' => Auth::id(),
                    'status' => 'loading',
                ]);
                $this->activeTrip->load('cargo.merchant', 'photos');
            }
        }

        // 3. Jika masih tidak ada, tampilkan trip terakhir (biasanya yang sudah completed)
        if (! $this->activeTrip) {
            $this->activeTrip = Trip::with(['cargo.merchant', 'photos'])->where('driver_id', Auth::id())
                ->where('status', 'completed')
                ->latest()
                ->first();
        }
    }

    public function startDriving()
    {
        if ($this->activeTrip) {
            $this->activeTrip->update(['status' => 'in_transit']);
            session()->flash('message', 'Perjalanan dimulai. Tracking aktif.');
        }
    }

    public function uploadPhoto($type)
    {
        $this->validate([
            'photo' => 'image|max:5120', // 5MB Max
        ]);

        $path = $this->photo->store('trip-photos', 'public');

        TripPhoto::create([
            'trip_id' => $this->activeTrip->id,
            'type' => $type,
            'path' => $path,
        ]);

        if ($type === 'loading') {
            session()->flash('message', 'Foto muat berhasil diunggah.');
        } else {
            $this->activeTrip->update(['status' => 'completed']);
            $this->activeTrip->cargo->update(['status' => 'done']);
            session()->flash('message', 'Foto bongkar berhasil diunggah. Trip Selesai.');
        }

        $this->photo = null;
        $this->activeTrip->refresh();
    }

    public function updateLocation($lat, $lng)
    {
        if ($this->activeTrip && $this->activeTrip->status === 'in_transit') {
            $trip = $this->activeTrip;

            // Dwell time detection
            $isStopped = false;
            if ($trip->current_lat && $trip->current_lng) {
                $distance = $this->calculateDistance($trip->current_lat, $trip->current_lng, $lat, $lng);
                if ($distance < 10) { // meters
                    $isStopped = true;
                }
            }

            if ($isStopped && $trip->last_moved_at) {
                $minutesStopped = now()->diffInMinutes($trip->last_moved_at);
                if ($minutesStopped >= 5) {
                    $recentDwell = TripEvent::where('trip_id', $trip->id)
                        ->where('type', 'dwell')
                        ->where('created_at', '>=', now()->subMinutes(10))
                        ->first();

                    if (! $recentDwell) {
                        TripEvent::create([
                            'trip_id' => $trip->id,
                            'type' => 'dwell',
                            'location_name' => 'Lokasi Tidak Diketahui',
                            'duration_minutes' => $minutesStopped,
                            'lat' => $lat,
                            'lng' => $lng,
                            'notes' => 'Pemberhentian Terdeteksi GPS',
                        ]);
                    }
                }
            }

            // Deviation detection (mock)
            $deviated = false;
            if ($trip->cargo && $trip->cargo->origin_lat && $trip->cargo->origin_lng) {
                $distFromOrigin = $this->calculateDistance($trip->cargo->origin_lat, $trip->cargo->origin_lng, $lat, $lng);
                if ($distFromOrigin > ($trip->admin_radius_m * 10)) {
                    $deviated = true;
                }
            }

            $trip->update([
                'current_lat' => $lat,
                'current_lng' => $lng,
                'last_moved_at' => $isStopped ? $trip->last_moved_at : now(),
                'is_deviated' => $deviated,
            ]);
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function toggleStopForm()
    {
        $this->showStopForm = ! $this->showStopForm;
        if ($this->showStopForm) {
            $this->showDeviationForm = false;
        }
    }

    public function toggleDeviationForm()
    {
        $this->showDeviationForm = ! $this->showDeviationForm;
        if ($this->showDeviationForm) {
            $this->showStopForm = false;
        }
    }

    public function reportStop()
    {
        $this->validate([
            'stopReason' => 'required|string',
            'stopDuration' => 'required|numeric|min:1',
            'photo' => 'nullable|image|max:5120',
        ]);

        if ($this->activeTrip) {
            $event = TripEvent::create([
                'trip_id' => $this->activeTrip->id,
                'type' => 'dwell',
                'location_name' => 'Lokasi Manual (Dilaporkan Driver)',
                'duration_minutes' => $this->stopDuration,
                'lat' => $this->activeTrip->current_lat,
                'lng' => $this->activeTrip->current_lng,
                'notes' => $this->stopReason,
            ]);

            if ($this->photo) {
                $path = $this->photo->store('trip-photos', 'public');
                TripPhoto::create([
                    'trip_id' => $this->activeTrip->id,
                    'type' => 'checkin',
                    'path' => $path,
                ]);
                $this->photo = null;
            }

            session()->flash('message', 'Laporan pemberhentian berhasil dicatat.');
            $this->showStopForm = false;
            $this->stopReason = '';
            $this->stopDuration = '';
        }
    }

    public function reportDeviation()
    {
        $this->validate([
            'deviationReason' => 'required|string',
        ]);

        if ($this->activeTrip) {
            TripEvent::create([
                'trip_id' => $this->activeTrip->id,
                'type' => 'deviation',
                'location_name' => 'Lokasi Manual (Dilaporkan Driver)',
                'duration_minutes' => 0,
                'lat' => $this->activeTrip->current_lat,
                'lng' => $this->activeTrip->current_lng,
                'notes' => $this->deviationReason,
            ]);

            session()->flash('message', 'Laporan deviasi jalur berhasil dicatat.');
            $this->showDeviationForm = false;
            $this->deviationReason = '';
        }
    }

    public function openFlashSale()
    {
        if ($this->activeTrip && $this->activeTrip->cargo) {
            $cargo = $this->activeTrip->cargo;
            
            // Calculate available weight (assuming driver's vehicle has some capacity, but let's just use what's left or a default 1000kg for now)
            $vehicleCapacity = 3000; // Mock 3000kg for now
            $availableWeight = $vehicleCapacity - ($cargo->weight_kg ?? 0);

            if ($availableWeight > 0) {
                // Set flash sale expiration to 2 hours from now
                $this->activeTrip->update([
                    'flash_sale_expires_at' => now()->addHours(2)
                ]);

                // Create a new Flash Sale Load
                $flashLoad = \App\Models\Load::create([
                    'merchant_id' => Auth::id(), // Driver acts as merchant for this LTL
                    'type' => 'LTL',
                    'title' => 'Sisa Muatan ' . $this->activeTrip->id,
                    'item_name' => 'Bebas',
                    'weight_kg' => $availableWeight,
                    'available_weight' => $availableWeight,
                    'vehicle_type_needed' => $cargo->vehicle_type_needed ?? 'Pickup',
                    'max_price' => $cargo->max_price * 0.5, // 50% discount
                    'status' => 'open',
                    'escrow_status' => 'pending',
                    'bid_deadline' => now()->addHours(2),
                ]);

                // Auto post to Social Feed
                \App\Models\Post::create([
                    'user_id' => Auth::id(),
                    'content' => 'Sisa muatan ' . $availableWeight . ' KG rute ' . ($cargo->sender_address ?? 'Jakarta') . ' ke ' . ($cargo->receiver_address ?? 'Tujuan') . ' diskon 50%! Cek menu Bursa Muatan sekarang!',
                ]);

                session()->flash('message', 'Flash Sale berhasil dibuka! 2 Jam batas waktu pencarian tambahan muatan.');
            } else {
                session()->flash('error', 'Kapasitas kendaraan sudah penuh.');
            }
        }
    }

    public function render()
    {
        return view('livewire.driver-cockpit');
    }
}
