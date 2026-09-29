<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Load;
use App\Models\Bid;
use App\Models\Trip;
use App\Models\Rating;
use App\Models\VerificationRequest;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CargoFlowDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Merchant User
        $merchant = User::firstOrCreate(
            ['email' => 'pt.sinar.jaya@kargokita.com'],
            [
                'name' => 'PT Sinar Jaya',
                'password' => Hash::make('password'),
                'is_subscribed' => true,
                'tier' => 'trusted'
            ]
        );
        if (!$merchant->hasRole('merchant')) $merchant->assignRole('merchant');

        // 2. Driver pending HSE assessment
        $driverPending = User::firstOrCreate(
            ['email' => 'budi.santoso@kargokita.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'tier' => 'common'
            ]
        );
        if (!$driverPending->hasRole('driver')) $driverPending->assignRole('driver');
        
        VerificationRequest::updateOrCreate(
            ['user_id' => $driverPending->id],
            [
                'type' => 'driver',
                'data' => json_encode([
                    'identity' => ['full_name' => 'Budi Santoso', 'nik' => '327000111222333', 'phone' => '081234567890'],
                    'sim' => ['sim_type' => 'B1 Umum', 'sim_validity' => '2028-12-12', 'sim_number' => '987654321'],
                    'vehicle' => ['vehicle_type' => 'CDD Box', 'license_plate' => 'B 1234 CD', 'capacity_kg' => '2000'],
                    'compliance' => ['apar' => true, 'stopper' => true, 'kanebo' => false]
                ]),
                'status' => 'pending'
            ]
        );

        // 3. Three existing drivers with different metrics for bidding
        $driverA = User::firstOrCreate(
            ['email' => 'agus.setiawan@kargokita.com'],
            ['name' => 'Agus Setiawan', 'password' => Hash::make('password'), 'tier' => 'bronze', 'is_subscribed' => true]
        );
        if (!$driverA->hasRole('driver')) $driverA->assignRole('driver');

        $driverB = User::firstOrCreate(
            ['email' => 'bambang.suryadi@kargokita.com'],
            ['name' => 'Bambang Suryadi', 'password' => Hash::make('password'), 'tier' => 'silver', 'is_subscribed' => true]
        );
        if (!$driverB->hasRole('driver')) $driverB->assignRole('driver');

        $driverC = User::firstOrCreate(
            ['email' => 'candra.gunawan@kargokita.com'],
            ['name' => 'Candra Gunawan', 'password' => Hash::make('password'), 'tier' => 'gold', 'is_subscribed' => true]
        );
        if (!$driverC->hasRole('driver')) $driverC->assignRole('driver');

        // Create a dummy past load to attach trips to for ratings
        $pastLoad = Load::firstOrCreate([
            'merchant_id' => $merchant->id,
            'title' => 'Muatan Masa Lalu',
            'type' => 'FTL',
            'weight_kg' => 1000,
            'vehicle_type_needed' => 'CDD Box',
            'sender_name' => 'PT',
            'sender_phone' => '123',
            'sender_address' => 'A',
            'receiver_name' => 'PT2',
            'receiver_phone' => '123',
            'receiver_address' => 'B',
            'max_price' => 1000,
            'status' => 'closed'
        ]);

        // Driver A: 5 trips, 3 completed = 60%
        for($i=0; $i<5; $i++) {
            $t = Trip::create(['driver_id' => $driverA->id, 'load_id' => $pastLoad->id, 'status' => $i < 3 ? 'completed' : 'cancelled']);
            if ($i == 0) Rating::updateOrCreate(['trip_id' => $t->id, 'rater_id' => $merchant->id, 'ratee_id' => $driverA->id], ['score' => 4.0, 'review' => 'Standard']);
        }
        
        // Driver B: 10 trips, 9 completed = 90%
        for($i=0; $i<10; $i++) {
            $t = Trip::create(['driver_id' => $driverB->id, 'load_id' => $pastLoad->id, 'status' => $i < 9 ? 'completed' : 'in_transit']);
            if ($i == 0) Rating::updateOrCreate(['trip_id' => $t->id, 'rater_id' => $merchant->id, 'ratee_id' => $driverB->id], ['score' => 4.8, 'review' => 'Bagus']);
        }

        // Driver C: 20 trips, 20 completed = 100%
        for($i=0; $i<20; $i++) {
            $t = Trip::create(['driver_id' => $driverC->id, 'load_id' => $pastLoad->id, 'status' => 'completed']);
            if ($i == 0) Rating::updateOrCreate(['trip_id' => $t->id, 'rater_id' => $merchant->id, 'ratee_id' => $driverC->id], ['score' => 5.0, 'review' => 'Sangat Memuaskan']);
        }

        // 4. Create an Open Load for demo
        $load = Load::create([
            'merchant_id' => $merchant->id,
            'type' => 'FTL',
            'title' => 'Pengiriman Elektronik Cikarang ke Bandung',
            'item_name' => 'Alat Elektronik',
            'weight_kg' => 2000,
            'vehicle_type_needed' => 'CDD Box',
            'sender_name' => 'PT Maju Bersama',
            'sender_phone' => '08111222333',
            'sender_address' => 'Cikarang Industrial Estate',
            'receiver_name' => 'Toko Elektronik Makmur',
            'receiver_phone' => '08999888777',
            'receiver_address' => 'Bandung Kota',
            'max_price' => 3000000,
            'is_paylater' => true,
            'status' => 'open',
            'escrow_status' => 'pending'
        ]);

        // 5. Create Bids on the new load
        // Driver A offers cheapest bid, lowest rating/success
        Bid::create([
            'load_id' => $load->id,
            'driver_id' => $driverA->id,
            'amount' => 1500000,
            'status' => 'pending'
        ]);

        // Driver B offers middle bid, good rating/success
        Bid::create([
            'load_id' => $load->id,
            'driver_id' => $driverB->id,
            'amount' => 1800000,
            'status' => 'pending'
        ]);

        // Driver C offers highest bid (but within budget), perfect rating/success
        Bid::create([
            'load_id' => $load->id,
            'driver_id' => $driverC->id,
            'amount' => 2500000,
            'status' => 'pending'
        ]);
        
        $this->command->info('Data sample CargoFlow (HSE Assessment, Merchant Bids, Paylater) berhasil digenerate.');
    }
}
