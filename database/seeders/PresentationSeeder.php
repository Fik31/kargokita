<?php

namespace Database\Seeders;

use App\Models\Bid;
use App\Models\Load;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PresentationSeeder extends Seeder
{
    public function run()
    {
        // 1. Dapatkan user Merchant dan Driver
        $merchant = User::where('email', 'maju.logistik@kargokita.com')->first();
        $driver1 = User::where('email', 'adi@kargokita.com')->first();
        $driver2 = User::where('email', 'dodi@kargokita.com')->first();
        $driver3 = User::where('email', 'bambang@kargokita.com')->first();
        $driver4 = User::firstOrCreate(
            ['email' => 'ujang@kargokita.com'],
            ['name' => 'Ujang Solihin', 'password' => bcrypt('password'), 'tier' => 'bronze']
        );
        $driver4->assignRole('driver');

        // Removed fake stats update since successful_delivery_percentage is dynamic

        // 2. Buat Data Muatan (Load) khusus untuk presentasi
        if ($merchant) {
            $load = Load::create([
                'merchant_id' => $merchant->id,
                'title' => 'Pengiriman Elektronik TV & AC',
                'item_name' => 'Barang Elektronik (TV, AC, Kulkas)',
                'type' => 'FTL',
                'total_weight' => 2000,
                'weight_kg' => 2000,
                'koli' => 150,
                'available_weight' => 0,
                'vehicle_type_needed' => 'CDD Box',
                'max_price' => 1000000, // Harga terlalu murah untuk JKT-BDG
                'status' => 'open',
                'sender_name' => 'Bpk. Ahmad (Gudang)',
                'sender_phone' => '081234567890',
                'sender_address' => 'Gudang Maju, Tanjung Priok, Jakarta Utara',
                'receiver_name' => 'Toko Laris Manis',
                'receiver_phone' => '089876543210',
                'receiver_address' => 'Jl. Braga No 10, Bandung, Jawa Barat',
                'distance' => 150,
                'bid_deadline' => Carbon::now()->addHours(2), // 2 Jam dari sekarang
            ]);

            // 3. Driver menolak (Memberikan Saran Harga)
            if ($driver1) {
                Bid::create([
                    'load_id' => $load->id,
                    'driver_id' => $driver1->id,
                    'amount' => 0,
                    'suggested_price' => 1500000,
                    'status' => 'rejected',
                ]);
            }

            if ($driver2) {
                Bid::create([
                    'load_id' => $load->id,
                    'driver_id' => $driver2->id,
                    'amount' => 0,
                    'suggested_price' => 2000000,
                    'status' => 'rejected',
                ]);
            }

            if ($driver4) {
                Bid::create([
                    'load_id' => $load->id,
                    'driver_id' => $driver4->id,
                    'amount' => 0,
                    'suggested_price' => 3000000,
                    'status' => 'rejected',
                ]);
            }

            // 4. Ada driver yang akhirnya mengambil bid (Meskipun harga murah)
            if ($driver3) {
                Bid::create([
                    'load_id' => $load->id,
                    'driver_id' => $driver3->id,
                    'amount' => 950000, // Driver ini malah bid sedikit di bawah harga maksimal
                    'status' => 'pending',
                ]);
            }
        }
    }
}
