<?php

namespace App\Livewire;

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
                    'drop_points' => [['name' => 'Hub Kediri', 'lat' => -7.8228, 'lng' => 112.0118]]
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
        $dummyCargo = new \App\Models\Load();
        $dummyCargo->origin_name = 'Yogyakarta';
        $dummyCargo->origin_lat = -7.797068;
        $dummyCargo->origin_lng = 110.370529;
        $dummyCargo->dest_name = 'Malang';
        $dummyCargo->dest_lat = -7.983908;
        $dummyCargo->dest_lng = 112.621391;
        $dummyCargo->drop_points = json_encode([
            ['name' => 'Hub Kediri', 'lat' => -7.8228, 'lng' => 112.0118]
        ]);

        $dummyTrip = new Trip();
        $dummyTrip->id = 12; // TRK-012
        $dummyTrip->status = 'in_transit';
        $dummyTrip->is_deviated = false;
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
                $dummyCargo = new \App\Models\Load();
                $dummyCargo->origin_name = 'Yogyakarta';
                $dummyCargo->dest_name = 'Malang';
                $trip = new Trip();
                $trip->id = 12;
                $trip->is_deviated = false;
                $trip->setRelation('cargo', $dummyCargo);

                $dummyEvent = new TripEvent();
                $dummyEvent->type = 'dwell';
                $dummyEvent->location_name = 'Hub Solo (Bongkar Muat)';
                $dummyEvent->notes = 'Proses bongkar muat reguler berjalan lancar.';
                $dummyEvent->created_at = now()->subHours(1);
                
                if ($this->eventTab === 'dwell') {
                    $events = collect([$dummyEvent]);
                }
                
                $allEventsCount = ['dwell' => 1, 'deviation' => 0];
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
