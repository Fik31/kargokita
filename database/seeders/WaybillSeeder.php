<?php

namespace Database\Seeders;

use App\Models\Trip;
use App\Models\Waybill;
use Illuminate\Database\Seeder;

class WaybillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trips = Trip::where('status', 'completed')->get();
        
        foreach ($trips as $trip) {
            Waybill::create([
                'trip_id' => $trip->id,
                'status' => 'completed',
                'driver_signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=', // transparent 1x1 pixel base64
                'merchant_signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=',
            ]);
        }

        $activeTrips = Trip::where('status', 'in_transit')->get();
        foreach ($activeTrips as $trip) {
            Waybill::create([
                'trip_id' => $trip->id,
                'status' => 'pending',
                'driver_signature' => null,
                'merchant_signature' => null,
            ]);
        }
    }
}
