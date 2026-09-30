<?php

namespace App\Livewire;

use App\Models\Bid;
use App\Models\Dispute;
use App\Models\Load;
use App\Models\Post;
use App\Models\Trip;
use App\Models\TripEvent;
use App\Models\TripPhoto;
use App\Models\User;
use App\Notifications\TripCompletedEscrowNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class DriverCockpit extends Component
{
    use WithFileUploads;

    public $photo;

    public $activeTrip;

    public $activeTab = 'loading';

    // Loading photos
    public $photo_arrival;

    public $photo_loading;

    public $photo_loaded;

    public $document_loading;

    // Unloading photos
    public $photo_destination;

    public $photo_unloading;

    public $document_unloading;

    // Flash Sale Properties
    public $showFlashSaleForm = false;
    public $flash_sale_pickup_location = '';

    // Report properties
    public $showStopForm = false;

    public $showDeviationForm = false;

    public $showUrgentForm = false;

    public $urgentType = '';

    public $urgentReason = '';

    public $stopReason = '';

    public $stopDuration = '';

    public $deviationReason = '';

    public $activeTrips;

    public function mount()
    {
        // 1. Cek trip yang sedang berjalan
        $this->activeTrips = Trip::with(['cargo.merchant', 'photos'])->where('driver_id', Auth::id())
            ->whereIn('status', ['loading', 'in_transit', 'unloading'])
            ->get();

        $this->activeTrip = $this->activeTrips->first();

        // 2. Jika tidak ada trip berjalan, cek apakah ada bid yang diterima dan belum dibuatkan trip
        if (! $this->activeTrip) {
            $acceptedBids = Auth::user()->bids()->where('status', 'accepted')->whereHas('cargo', function ($q) {
                $q->whereIn('status', ['in_transit', 'open', 'assigned']);
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

        if ($this->activeTrip) {
            $this->activeTab = $this->activeTrip->status;
            if ($this->activeTab === 'completed') {
                $this->activeTab = 'loading'; // Default to first tab just in case
            }
        }
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    private function storeAndWatermark($photo, $type)
    {
        $path = $photo->store('trip-photos', 'public');
        // $this->processWatermark($path); // Disabled because watermark is now generated on client canvas

        TripPhoto::create([
            'trip_id' => $this->activeTrip->id,
            'type' => $type,
            'path' => $path,
        ]);
    }

    private function processWatermark($path)
    {
        $fullPath = storage_path('app/public/'.$path);
        if (! file_exists($fullPath)) {
            return $path;
        }

        $mime = mime_content_type($fullPath);
        if ($mime == 'image/jpeg') {
            $image = @imagecreatefromjpeg($fullPath);
        } elseif ($mime == 'image/png') {
            $image = @imagecreatefrompng($fullPath);
        } else {
            return $path;
        }

        if (! $image) {
            return $path;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        // Resize to 800px max width for readability of built-in font
        $maxWidth = 800;
        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = floor($height * ($maxWidth / $width));
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            // preserve transparency for png
            if ($mime == 'image/png') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
            }
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $newImage;
            $width = $newWidth;
            $height = $newHeight;
        }

        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocatealpha($image, 0, 0, 0, 50);

        $lat = $this->activeTrip->current_lat ?? 'Menunggu GPS';
        $lng = $this->activeTrip->current_lng ?? 'Menunggu GPS';
        $timestamp = now()->format('Y-m-d H:i:s');
        $text1 = 'Waktu : '.$timestamp;
        $text2 = 'Lokasi: '.$lat.', '.$lng;
        $text3 = 'Driver: '.Auth::user()->name;

        $boxWidth = 350;
        $boxHeight = 65;

        imagefilledrectangle($image, 10, $height - $boxHeight - 10, 10 + $boxWidth, $height - 10, $black);
        imagestring($image, 5, 20, $height - $boxHeight, $text1, $white);
        imagestring($image, 5, 20, $height - $boxHeight + 20, $text2, $white);
        imagestring($image, 5, 20, $height - $boxHeight + 40, $text3, $white);

        if ($mime == 'image/jpeg') {
            imagejpeg($image, $fullPath, 90);
        } elseif ($mime == 'image/png') {
            imagepng($image, $fullPath);
        }

        imagedestroy($image);

        return $path;
    }

    public function submitLoadingPhotos()
    {
        $this->validate([
            'photo_arrival' => 'required|image|max:5120',
            'photo_loading' => 'required|image|max:5120',
            'photo_loaded' => 'required|image|max:5120',
            'document_loading' => 'nullable|image|max:5120',
        ]);

        if ($this->photo_arrival) {
            $this->storeAndWatermark($this->photo_arrival, 'arrival');
        }
        if ($this->photo_loading) {
            $this->storeAndWatermark($this->photo_loading, 'loading_process');
        }
        if ($this->photo_loaded) {
            $this->storeAndWatermark($this->photo_loaded, 'loading_completed');
        }
        if ($this->document_loading) {
            $this->storeAndWatermark($this->document_loading, 'document_loading');
        }

        $this->activeTrip->update(['status' => 'in_transit']);

        // Also update all loading trips to in_transit if LTL
        foreach ($this->activeTrips as $trip) {
            if ($trip->status === 'loading') {
                $trip->update(['status' => 'in_transit']);
            }
        }

        $this->activeTab = 'in_transit';
        session()->flash('message', 'Perjalanan dimulai. Tracking aktif.');
    }

    public function arriveAtDestination()
    {
        if ($this->activeTrip && $this->activeTrip->status === 'in_transit') {
            $this->activeTrip->update(['status' => 'unloading']);

            // Update all to unloading if they were in transit
            foreach ($this->activeTrips as $trip) {
                if ($trip->status === 'in_transit') {
                    $trip->update(['status' => 'unloading']);
                }
            }

            $this->activeTab = 'unloading';
            session()->flash('message', 'Tiba di tujuan. Silakan lakukan proses bongkar.');
        }
    }

    public function submitUnloadingPhotos()
    {
        $this->validate([
            'photo_destination' => 'required|image|max:5120',
            'photo_unloading' => 'required|image|max:5120',
            'document_unloading' => 'nullable|image|max:5120',
        ]);

        if ($this->photo_destination) {
            $this->storeAndWatermark($this->photo_destination, 'destination');
        }
        if ($this->photo_unloading) {
            $this->storeAndWatermark($this->photo_unloading, 'unloading_process');
        }
        if ($this->document_unloading) {
            $this->storeAndWatermark($this->document_unloading, 'document_unloading');
        }

        $this->activeTrip->update(['status' => 'completed']);
        if ($this->activeTrip->cargo) {
            $this->activeTrip->cargo->update(['status' => 'done']);

            // Check if cargo uses paylater and escrow is still pending
            if ($this->activeTrip->cargo->is_paylater && $this->activeTrip->cargo->escrow_status === 'pending') {
                $admins = User::role('administrator')->get();
                Notification::send($admins, new TripCompletedEscrowNotification($this->activeTrip));
            }
        }

        // Update all unloading to completed
        foreach ($this->activeTrips as $trip) {
            if ($trip->status === 'unloading') {
                $trip->update(['status' => 'completed']);
                if ($trip->cargo) {
                    $trip->cargo->update(['status' => 'done']);

                    if ($trip->cargo->is_paylater && $trip->cargo->escrow_status === 'pending') {
                        $admins = User::role('administrator')->get();
                        Notification::send($admins, new TripCompletedEscrowNotification($trip));
                    }
                }
            }
        }

        session()->flash('message', 'Foto bongkar berhasil diunggah. Trip Selesai.');
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
            $this->showUrgentForm = false;
        }
    }

    public function toggleDeviationForm()
    {
        $this->showDeviationForm = ! $this->showDeviationForm;
        if ($this->showDeviationForm) {
            $this->showStopForm = false;
            $this->showUrgentForm = false;
        }
    }

    public function toggleUrgentForm()
    {
        $this->showUrgentForm = ! $this->showUrgentForm;
        if ($this->showUrgentForm) {
            $this->showStopForm = false;
            $this->showDeviationForm = false;
        }
    }

    public function reportStop()
    {
        $this->validate([
            'stopReason' => 'required|string',
            'stopDuration' => 'required|numeric|min:1',
            'photo' => 'required|image|max:5120',
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
                $this->storeAndWatermark($this->photo, 'checkin');
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
            'photo' => 'required|image|max:5120',
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

            if ($this->photo) {
                $this->storeAndWatermark($this->photo, 'deviation');
                $this->photo = null;
            }

            session()->flash('message', 'Laporan deviasi jalur berhasil dicatat.');
            $this->showDeviationForm = false;
            $this->deviationReason = '';
        }
    }

    public function reportUrgentIssue()
    {
        $this->validate([
            'urgentType' => 'required|string',
            'urgentReason' => 'required|string|min:10',
            'photo' => 'required|image|max:5120',
        ]);

        if ($this->activeTrip && $this->activeTrip->cargo) {
            $cargo = $this->activeTrip->cargo;

            // 1. Mark current trip as SOS / failed
            $this->activeTrip->update(['status' => 'sos']);

            // 2. Upload photo
            $path = $this->photo->store('dispute-photos', 'public');

            // 3. Create Dispute/Arbitration
            Dispute::create([
                'trip_id' => $this->activeTrip->id,
                'load_id' => $cargo->id,
                'reporter_id' => Auth::id(),
                'type' => $this->urgentType,
                'reason' => $this->urgentReason,
                'status' => 'open',
                'resolution_notes' => 'Foto kejadian: '.$path,
            ]);

            // 4. Republish Load as Urgent to find replacement driver
            // Get original winning bid price if possible, or use max_price
            $winningBid = Bid::where('load_id', $cargo->id)->where('status', 'accepted')->first();
            $newPrice = $winningBid ? $winningBid->amount : $cargo->max_price;

            // We can either update existing load or create a new one. Let's create a new urgent load
            $newLoad = Load::create([
                'merchant_id' => $cargo->merchant_id,
                'type' => $cargo->type,
                'title' => '[URGENT] '.$cargo->title,
                'item_name' => $cargo->item_name,
                'total_weight' => $cargo->total_weight,
                'available_weight' => $cargo->available_weight,
                'weight_kg' => $cargo->weight_kg,
                'koli' => $cargo->koli,
                'vehicle_type_needed' => $cargo->vehicle_type_needed,
                'max_price' => $newPrice, // Keep the same price
                'status' => 'open',
                'origin_lat' => $this->activeTrip->current_lat ?? $cargo->origin_lat,
                'origin_lng' => $this->activeTrip->current_lng ?? $cargo->origin_lng,
                'dest_lat' => $cargo->dest_lat,
                'dest_lng' => $cargo->dest_lng,
                'route_polyline' => $cargo->route_polyline,
                'sender_name' => $cargo->sender_name,
                'sender_phone' => $cargo->sender_phone,
                'sender_address' => 'Lokasi Terkini Driver Sebelumnya',
                'receiver_name' => $cargo->receiver_name,
                'receiver_phone' => $cargo->receiver_phone,
                'receiver_address' => $cargo->receiver_address,
                'distance' => $cargo->distance, // Should ideally recalculate
                'bid_deadline' => now()->addHours(6),
                'escrow_status' => 'pending',
                'is_paylater' => $cargo->is_paylater,
                'is_urgent' => true,
                'required_equipments' => $cargo->required_equipments,
            ]);

            // Also set old cargo to done/failed
            $cargo->update(['status' => 'sos']);

            session()->flash('message', 'Keadaan Darurat berhasil dilaporkan. Muatan telah dipublikasikan ulang sebagai URGENT dan tim Admin/HSE akan segera melakukan mediasi.');
            $this->showUrgentForm = false;
            $this->activeTab = 'sos';

            return redirect()->route('dashboard');
        }
    }

    public function toggleFlashSaleForm()
    {
        $this->showFlashSaleForm = !$this->showFlashSaleForm;
    }

    public function openFlashSale()
    {
        $this->validate([
            'flash_sale_pickup_location' => 'required|string|max:255',
        ]);

        if ($this->activeTrip && $this->activeTrip->cargo) {
            $cargo = $this->activeTrip->cargo;

            // Calculate available weight
            $vehicleCapacity = 3000; // Mock 3000kg for now
            $availableWeight = $vehicleCapacity - ($cargo->weight_kg ?? 0);

            if ($availableWeight > 0) {
                // Set flash sale expiration to 2 hours from now
                $this->activeTrip->update([
                    'flash_sale_expires_at' => now()->addHours(2),
                ]);

                // Harga 1000/kg otomatis by system
                $price = $availableWeight * 1000;

                // Create a new Flash Sale Load
                $flashLoad = Load::create([
                    'merchant_id' => Auth::id(), // Driver acts as merchant for this LTL
                    'type' => 'LTL',
                    'title' => 'LTL Flash Sale ' . $this->flash_sale_pickup_location . ' - ' . ($cargo->receiver_address ?? 'Tujuan Akhir'),
                    'item_name' => 'Bebas',
                    'weight_kg' => $availableWeight,
                    'available_weight' => $availableWeight,
                    'vehicle_type_needed' => $cargo->vehicle_type_needed ?? 'Pickup',
                    'max_price' => $price, // Fixed 1000/kg
                    'sender_address' => $this->flash_sale_pickup_location,
                    'receiver_address' => $cargo->receiver_address,
                    'status' => 'open',
                    'escrow_status' => 'pending',
                    'bid_deadline' => now()->addHours(2),
                ]);

                // Auto post to Social Feed as a broadcast / notification
                Post::create([
                    'user_id' => Auth::id(),
                    'content' => 'Sisa muatan '.$availableWeight.' KG rute '.$this->flash_sale_pickup_location.' ke '.($cargo->receiver_address ?? 'Tujuan').'. Tarif super miring otomatis by system (1000/kg). Cek menu Bursa Muatan sekarang!',
                ]);

                session()->flash('message', 'Flash Sale (LTL) berhasil dibuka dari titik '.$this->flash_sale_pickup_location.'. Harga otomatis diset Rp'.number_format($price, 0, ',', '.').' (1000/kg). Menunggu merchant.');
                $this->showFlashSaleForm = false;
                $this->flash_sale_pickup_location = '';
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
