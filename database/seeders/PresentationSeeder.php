<?php

namespace Database\Seeders;

use App\Models\Load;
use App\Models\Post;
use App\Models\Trip;
use App\Models\TripEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PresentationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Dapatkan user merchant dan driver
        $merchant = User::where('email', 'merchant.vip@kargokita.com')->first();
        $driver = User::where('email', 'driver.vip@kargokita.com')->first();

        if (! $merchant || ! $driver) {
            echo "User merchant VIP atau driver VIP tidak ditemukan.\n";

            return;
        }

        // 2. Buat Loads Dummy
        $loads = [];
        for ($i = 1; $i <= 5; $i++) {
            $loads[] = Load::create([
                'merchant_id' => $merchant->id,
                'type' => 'FTL',
                'total_weight' => rand(2, 5),
                'available_weight' => 0,
                'max_price' => rand(3000, 8000) * 1000,
                'status' => 'done',
                'origin_lat' => -6.175110 + (rand(-10, 10) / 1000),
                'origin_lng' => 106.865036 + (rand(-10, 10) / 1000),
                'dest_lat' => -7.250445 + (rand(-10, 10) / 1000),
                'dest_lng' => 112.768845 + (rand(-10, 10) / 1000),
                'drop_points' => null,
                'created_at' => Carbon::now()->subDays(rand(2, 10)),
                'updated_at' => Carbon::now()->subDays(rand(1, 9)),
            ]);
        }

        // 3. Buat Completed Trips untuk Loads tersebut
        foreach ($loads as $index => $load) {
            $trip = Trip::create([
                'load_id' => $load->id,
                'driver_id' => $driver->id,
                'status' => 'completed',
                'current_lat' => $load->dest_lat,
                'current_lng' => $load->dest_lng,
                'admin_radius_m' => 100,
                'is_deviated' => false,
                'created_at' => $load->created_at->addHours(1),
                'updated_at' => $load->created_at->addHours(rand(10, 24)),
            ]);

            // Tambahkan event (Pemberhentian / Deviasi) untuk masing-masing trip
            $numEvents = rand(1, 4);
            for ($e = 1; $e <= $numEvents; $e++) {
                $isDeviation = rand(1, 10) > 7; // 30% deviasi
                TripEvent::create([
                    'trip_id' => $trip->id,
                    'type' => $isDeviation ? 'deviation' : 'dwell',
                    'location_name' => $isDeviation ? 'Jalur Alternatif Pantura' : 'Rest Area KM '.(rand(50, 400)),
                    'duration_minutes' => $isDeviation ? 0 : rand(15, 60),
                    'lat' => $load->origin_lat + (($load->dest_lat - $load->origin_lat) * ($e / 5)),
                    'lng' => $load->origin_lng + (($load->dest_lng - $load->origin_lng) * ($e / 5)),
                    'notes' => $isDeviation ? 'Dialihkan karena perbaikan jalan' : 'Istirahat dan isi bahan bakar',
                    'created_at' => $trip->created_at->addHours($e * 2),
                ]);
            }
        }

        // 4. Buat Active Trip (In Transit) agar Peta Live Tracking ada datanya
        $activeLoad = Load::create([
            'merchant_id' => $merchant->id,
            'type' => 'LTL',
            'total_weight' => 5,
            'available_weight' => 2,
            'max_price' => 6000000,
            'status' => 'done', // Status 'done' di load, tapi 'in_transit' di trip
            'origin_lat' => -6.200000,
            'origin_lng' => 106.816666,
            'dest_lat' => -7.250445,
            'dest_lng' => 112.768845,
            'drop_points' => json_encode([
                ['name' => 'Hub Cirebon', 'lat' => -6.732, 'lng' => 108.552],
                ['name' => 'Hub Semarang', 'lat' => -6.993, 'lng' => 110.420],
            ]),
        ]);

        $activeTrip = Trip::create([
            'load_id' => $activeLoad->id,
            'driver_id' => $driver->id,
            'status' => 'in_transit',
            'current_lat' => -6.732, // Sedang di Cirebon
            'current_lng' => 108.552,
            'admin_radius_m' => 100,
            'is_deviated' => false,
        ]);

        TripEvent::create([
            'trip_id' => $activeTrip->id,
            'type' => 'dwell',
            'location_name' => 'Hub Cirebon (Bongkar LTL)',
            'duration_minutes' => 30,
            'lat' => -6.732,
            'lng' => 108.552,
            'notes' => 'Proses bongkar muatan LTL sebagian',
        ]);

        // 5. Tambahkan Feed Posts Dummy
        Post::create([
            'user_id' => $driver->id,
            'content' => 'Posisi saat ini di Cirebon, cuaca cerah namun jalanan sedikit padat. Target sampai Semarang sore nanti. 🚚💨',
            'type' => 'status',
            'created_at' => Carbon::now()->subMinutes(30),
        ]);

        Post::create([
            'user_id' => $merchant->id,
            'content' => 'Ada yang punya space kosong LTL dari Jakarta ke Surabaya malam ini? Muatan saya sekitar 1.5 Ton mesin pabrik.',
            'type' => 'seeking_driver',
            'created_at' => Carbon::now()->subHours(2),
        ]);

        Post::create([
            'user_id' => $driver->id,
            'content' => 'Truk engkel box masih kosong 2 Ton, siap jalan rute Semarang - Surabaya besok pagi. Yang butuh lemparan muatan silakan kontak!',
            'type' => 'seeking_load',
            'created_at' => Carbon::now()->subDays(1),
        ]);

        echo "Presentation Data Berhasil Dibuat!\n";
    }
}
