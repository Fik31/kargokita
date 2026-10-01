<?php

namespace Database\Seeders;

use App\Models\Bid;
use App\Models\Dispute;
use App\Models\Load;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DisputeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil Merchant
        $merchant = User::updateOrCreate(
            ['email' => 'pt.sinar.jaya@kargokita.com'],
            [
                'name' => 'PT Sinar Jaya',
                'password' => Hash::make('password'),
                'is_subscribed' => true,
                'tier' => 'premium',
            ]
        );
        if (! $merchant->hasRole('merchant')) {
            $merchant->assignRole('merchant');
        }

        // 2. Ambil Driver B yang akan mengalami masalah
        $driverB = User::updateOrCreate(
            ['email' => 'bambang.suryadi@kargokita.com'],
            ['name' => 'Bambang Suryadi', 'password' => Hash::make('password'), 'tier' => 'silver', 'is_subscribed' => true]
        );
        if (! $driverB->hasRole('driver')) {
            $driverB->assignRole('driver');
        }

        // 3. Buat Load yang sedang di-trip-kan
        $loadOriginal = Load::create([
            'merchant_id' => $merchant->id,
            'type' => 'FTL',
            'title' => 'Pengiriman Besi Bekas ke Pabrik (Gagal Lanjut)',
            'item_name' => 'Besi Bekas',
            'weight_kg' => 4000,
            'vehicle_type_needed' => 'Fuso',
            'sender_name' => 'Gudang PT Sinar Jaya',
            'sender_phone' => '08111222333',
            'sender_address' => 'Kawasan Industri Pulogadung, Jakarta',
            'sender_notes' => 'Harap bawa terpal.',
            'receiver_name' => 'Pabrik Peleburan',
            'receiver_phone' => '08999888777',
            'receiver_address' => 'Cilegon, Banten',
            'receiver_notes' => 'Masuk dari pintu timur.',
            'max_price' => 2000000,
            'is_paylater' => false,
            'status' => 'closed',
            'escrow_status' => 'pending',
            'is_urgent' => false,
        ]);

        Bid::create([
            'load_id' => $loadOriginal->id,
            'driver_id' => $driverB->id,
            'amount' => 1800000,
            'status' => 'accepted',
        ]);

        $trip = Trip::create([
            'driver_id' => $driverB->id,
            'load_id' => $loadOriginal->id,
            'status' => 'sos',
        ]);

        // 4. Buat Dispute
        Dispute::create([
            'trip_id' => $trip->id,
            'load_id' => $loadOriginal->id,
            'reporter_id' => $driverB->id,
            'type' => 'vehicle_breakdown',
            'reason' => 'Ban belakang pecah ganda di Tol Cikampek Km 12. Tidak ada serep dan bengkel terdekat sedang tutup.',
            'status' => 'open',
        ]);

        // 5. Buat Load Duplikat (Urgent SOS)
        Load::create([
            'merchant_id' => $merchant->id,
            'type' => 'FTL',
            'title' => 'URGENT SOS: '.$loadOriginal->title,
            'item_name' => $loadOriginal->item_name,
            'weight_kg' => $loadOriginal->weight_kg,
            'vehicle_type_needed' => $loadOriginal->vehicle_type_needed,
            'sender_name' => $loadOriginal->sender_name,
            'sender_phone' => $loadOriginal->sender_phone,
            'sender_address' => 'Tol Cikampek Km 12 (Lokasi Overload)',
            'sender_notes' => 'Meneruskan muatan truk yang mogok. Mohon segera.',
            'receiver_name' => $loadOriginal->receiver_name,
            'receiver_phone' => $loadOriginal->receiver_phone,
            'receiver_address' => $loadOriginal->receiver_address,
            'receiver_notes' => $loadOriginal->receiver_notes,
            'max_price' => 1800000, // Harga mengikuti bid yang disetujui sebelumnya
            'is_paylater' => $loadOriginal->is_paylater,
            'status' => 'open',
            'escrow_status' => 'pending',
            'is_urgent' => true,
        ]);

        $this->command->info('Data SOS / Urgent Dispute berhasil di-seed.');
    }
}
