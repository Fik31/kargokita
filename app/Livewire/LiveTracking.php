<?php

namespace App\Livewire;

use App\Models\Load;
use App\Models\Trip;
use App\Models\TripEvent;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class LiveTracking extends Component
{
    public $tripId;

    public $isDeviated = false;

    public $activeTab = 'leaflet'; // leaflet | schematic | topology

    public $eventTab = 'dwell'; // dwell | deviation

    public function mount()
    {
        $user = Auth::user();
        $query = Trip::with('cargo')->whereIn('status', ['loading', 'in_transit']);

        if ($user && $user->hasRole('merchant')) {
            $query->whereHas('cargo', function ($q) use ($user) {
                $q->where('merchant_id', $user->id);
            });
        }

        $trips = $query->get();

        if (count($trips) > 0) {
            $this->tripId = $trips->first()->id;
            $this->isDeviated = $trips->first()->is_deviated;
        } else {
            $this->tripId = 12;
            $this->isDeviated = false;
        }
    }

    public function selectTrip($id)
    {
        if ($this->tripId == $id) {
            // Collapse if already open
            $this->tripId = null;
        } else {
            $this->tripId = $id;
            if ($id == 12) {
                $this->isDeviated = false;
            } else {
                $trip = Trip::find($id);
                if ($trip) {
                    $this->isDeviated = $trip->is_deviated;
                }
            }
        }
        $this->dispatch('trip-changed');
    }

    public function getLatestLocation()
    {
        if ($this->tripId) {
            if ($this->tripId == 12) {
                return [
                    'lat' => -7.549416, // Presisi pada rute tol
                    'lng' => 111.538442,
                    'origin_lat' => -7.797068, // Jogja
                    'origin_lng' => 110.370529,
                    'dest_lat' => -7.983908, // Malang
                    'dest_lng' => 112.621391,
                    'is_deviated' => false,
                    'drop_points' => [['name' => 'Hub Kediri', 'lat' => -7.8228, 'lng' => 112.0118]],
                ];
            }

            $trip = Trip::with('cargo')->find($this->tripId);
            if ($trip) {
                return [
                    'lat' => $trip->current_lat ?? -6.700000, // Koordinat simulasi posisi saat ini (Cirebon)
                    'lng' => $trip->current_lng ?? 108.566666,
                    'origin_lat' => $trip->cargo->origin_lat ?? -6.200000, // Jakarta
                    'origin_lng' => $trip->cargo->origin_lng ?? 106.816666,
                    'dest_lat' => $trip->cargo->dest_lat ?? -7.250445, // Surabaya
                    'dest_lng' => $trip->cargo->dest_lng ?? 112.768845,
                    'is_deviated' => $trip->is_deviated,
                    'drop_points' => $trip->cargo && $trip->cargo->drop_points ? json_decode($trip->cargo->drop_points, true) : [],
                ];
            }
        }

        return null;
    }

    public function render()
    {
        // GET TRIPS
        $user = Auth::user();
        $query = Trip::with('cargo')->whereIn('status', ['loading', 'in_transit']);

        if ($user && $user->hasRole('merchant')) {
            $query->whereHas('cargo', function ($q) use ($user) {
                $q->where('merchant_id', $user->id);
            });
        }

        $activeTrips = $query->get();

        // --- INJECT DUMMY TRK-012 ---
        $dummyCargo = new Load;
        $dummyCargo->origin_name = 'Yogyakarta';
        $dummyCargo->origin_lat = -7.797068;
        $dummyCargo->origin_lng = 110.370529;
        $dummyCargo->dest_name = 'Malang';
        $dummyCargo->dest_lat = -7.983908;
        $dummyCargo->dest_lng = 112.621391;
        $dummyCargo->drop_points = json_encode([
            ['name' => 'Hub Kediri', 'lat' => -7.8228, 'lng' => 112.0118],
        ]);

        $dummyTrip = new Trip;
        $dummyTrip->id = 12; // TRK-012
        $dummyTrip->status = 'in_transit';
        $dummyTrip->is_deviated = true;
        $dummyTrip->current_lat = -7.549416; // Presisi pada rute tol OSRM
        $dummyTrip->current_lng = 111.538442;
        $dummyTrip->setRelation('cargo', $dummyCargo);

        $activeTrips->push($dummyTrip);
        // -----------------------------

        $events = [];
        $trip = null;
        $allEventsCount = [];

        if ($this->tripId) {
            if ($this->tripId == 12) {
                $dummyCargo = new Load;
                $dummyCargo->origin_name = 'Yogyakarta';
                $dummyCargo->dest_name = 'Malang';
                $trip = new Trip;
                $trip->id = 12;
                $trip->is_deviated = true;
                $trip->setRelation('cargo', $dummyCargo);

                $dwell1 = new TripEvent;
                $dwell1->type = 'dwell';
                $dwell1->location_name = 'Rest Area KM 429';
                $dwell1->notes = 'Istirahat driver dan pengecekan ban (30 menit).';
                $dwell1->created_at = now()->subHours(4);

                $dwell2 = new TripEvent;
                $dwell2->type = 'dwell';
                $dwell2->location_name = 'Hub Solo (Bongkar Muat)';
                $dwell2->notes = 'Proses bongkar muat reguler berjalan lancar (1 jam 15 menit).';
                $dwell2->created_at = now()->subHours(2);

                $dwell3 = new TripEvent;
                $dwell3->type = 'dwell';
                $dwell3->location_name = 'SPBU Nganjuk';
                $dwell3->notes = 'Pengisian bahan bakar Solar (15 menit).';
                $dwell3->created_at = now()->subMinutes(45);

                $dev1 = new TripEvent;
                $dev1->type = 'deviation';
                $dev1->location_name = 'Keluar Tol Ngawi';
                $dev1->notes = 'Driver keluar jalur tol (deviasi 5km) diduga mencari rute alternatif atau bengkel.';
                $dev1->created_at = now()->subHours(1)->subMinutes(30);

                if ($this->eventTab === 'dwell') {
                    $events = collect([$dwell3, $dwell2, $dwell1]);
                } else if ($this->eventTab === 'deviation') {
                    $events = collect([$dev1]);
                }

                $allEventsCount = ['dwell' => 3, 'deviation' => 1];
            } else {
                $trip = Trip::with(['cargo.merchant', 'driver'])->find($this->tripId);
                $events = TripEvent::where('trip_id', $this->tripId)
                    ->where('type', $this->eventTab)
                    ->latest()
                    ->get();

                $allEventsCount = TripEvent::where('trip_id', $this->tripId)
                    ->selectRaw('type, count(*) as count')
                    ->groupBy('type')
                    ->pluck('count', 'type')
                    ->toArray();
            }
        }

        return view('livewire.live-tracking', [
            'activeTrips' => $activeTrips,
            'events' => $events,
            'trip' => $trip,
            'dwellCount' => $allEventsCount['dwell'] ?? 0,
            'deviationCount' => $allEventsCount['deviation'] ?? 0,
        ]);
    }
}
