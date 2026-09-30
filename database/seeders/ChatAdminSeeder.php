<?php

namespace Database\Seeders;

use App\Models\Load;
use App\Models\Message;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ChatAdminSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('email', 'admin@kargokita.com')->first();
        $merchant = User::where('email', 'budi.merchant@kargokita.com')->first();
        $driver1 = User::where('email', 'adi@kargokita.com')->first();
        $driver2 = User::where('email', 'dodi@kargokita.com')->first();

        if (! $admin || ! $merchant || ! $driver1 || ! $driver2) {
            return;
        }

        // SCENARIO 1: ADMIN HANYA MEMANTAU (TIDAK ADA PERMINTAAN BANTUAN)
        $load1 = Load::create([
            'merchant_id' => $merchant->id,
            'title' => 'Pengiriman Beras 5 Ton',
            'item_name' => 'Beras Karungan',
            'type' => 'FTL',
            'total_weight' => 5000,
            'weight_kg' => 5000,
            'max_price' => 1500000,
            'status' => 'assigned',
            'bid_deadline' => Carbon::now()->addDay(),
        ]);

        $trip1 = Trip::create([
            'load_id' => $load1->id,
            'driver_id' => $driver1->id,
            'status' => 'assigned',
            'admin_assistance_requested' => false,
            'created_at' => Carbon::now()->subHours(2),
            'updated_at' => Carbon::now()->subHours(2),
        ]);

        Message::create([
            'trip_id' => $trip1->id,
            'sender_id' => $merchant->id,
            'receiver_id' => $driver1->id,
            'message' => 'Pak Adi, posisi sekarang dimana? Berasnya sudah siap diangkut.',
            'created_at' => Carbon::now()->subMinutes(50),
            'updated_at' => Carbon::now()->subMinutes(50),
        ]);

        Message::create([
            'trip_id' => $trip1->id,
            'sender_id' => $driver1->id,
            'receiver_id' => $merchant->id,
            'message' => 'Lagi di jalan pak, kena macet di pasar. Sekitar 20 menit lagi nyampe gudang bapak.',
            'created_at' => Carbon::now()->subMinutes(40),
            'updated_at' => Carbon::now()->subMinutes(40),
        ]);

        Message::create([
            'trip_id' => $trip1->id,
            'sender_id' => $merchant->id,
            'receiver_id' => $driver1->id,
            'message' => 'Oke hati-hati pak.',
            'created_at' => Carbon::now()->subMinutes(30),
            'updated_at' => Carbon::now()->subMinutes(30),
        ]);

        // SCENARIO 2: ADMIN IKUT NIMBRUNG KARENA ADA KERIBUTAN
        $load2 = Load::create([
            'merchant_id' => $merchant->id,
            'title' => 'Pengiriman Telur 1 Ton',
            'item_name' => 'Telur Ayam',
            'type' => 'LTL',
            'total_weight' => 1000,
            'weight_kg' => 1000,
            'max_price' => 700000,
            'status' => 'assigned',
            'bid_deadline' => Carbon::now()->addDay(),
        ]);

        $trip2 = Trip::create([
            'load_id' => $load2->id,
            'driver_id' => $driver2->id,
            'status' => 'assigned',
            'admin_assistance_requested' => true, // TOMBOL BANTUAN SUDAH DIKLIK
            'created_at' => Carbon::now()->subHours(3),
            'updated_at' => Carbon::now()->subHours(3),
        ]);

        Message::create([
            'trip_id' => $trip2->id,
            'sender_id' => $driver2->id,
            'receiver_id' => $merchant->id,
            'message' => 'Pak, ini telurnya ada yang pecah 2 peti dari sananya sebelum dimuat, saya gak mau tanggung jawab ya!',
            'created_at' => Carbon::now()->subMinutes(60),
            'updated_at' => Carbon::now()->subMinutes(60),
        ]);

        Message::create([
            'trip_id' => $trip2->id,
            'sender_id' => $merchant->id,
            'receiver_id' => $driver2->id,
            'message' => 'Lho kok gitu? Orang gudang saya bilang utuh semua waktu diserahkan ke bapak!',
            'created_at' => Carbon::now()->subMinutes(55),
            'updated_at' => Carbon::now()->subMinutes(55),
        ]);

        Message::create([
            'trip_id' => $trip2->id,
            'sender_id' => $driver2->id,
            'receiver_id' => $merchant->id,
            'message' => 'Saya ada fotonya pak pas baru dibuka di bak mobil! Bapak jangan asal nuduh driver.',
            'created_at' => Carbon::now()->subMinutes(50),
            'updated_at' => Carbon::now()->subMinutes(50),
        ]);

        Message::create([
            'trip_id' => $trip2->id,
            'sender_id' => $merchant->id,
            'receiver_id' => $driver2->id,
            'message' => 'Yaudah saya klik minta bantuan admin aja biar diurus.',
            'created_at' => Carbon::now()->subMinutes(45),
            'updated_at' => Carbon::now()->subMinutes(45),
        ]);

        // ADMIN IKUT NIMBRUNG
        Message::create([
            'trip_id' => $trip2->id,
            'sender_id' => $admin->id,
            'receiver_id' => $merchant->id, // Receiver id tidak terlalu relevan karena pakai trip_id
            'message' => 'Halo Bapak-bapak, harap tenang. Kepada pihak Driver, mohon unggah bukti fotonya ke menu Laporan (Report) di aplikasi. Kepada pihak Merchant, mohon ditunggu hasil investigasi tim kami sebelum melakukan klaim asuransi. Terima kasih.',
            'created_at' => Carbon::now()->subMinutes(10),
            'updated_at' => Carbon::now()->subMinutes(10),
        ]);
    }
}
