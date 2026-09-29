<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Load;
use App\Models\Bid;
use App\Models\Trip;
use App\Models\Message;
use App\Models\Notification; // Assuming this exists or we can just use posts or banners
use Carbon\Carbon;

class ChatSeeder extends Seeder
{
    public function run()
    {
        // 1. Ambil data dari PresentationSeeder
        $merchant = User::where('email', 'maju.logistik@kargokita.com')->first();
        $driver = User::where('email', 'bambang@kargokita.com')->first();

        if (!$merchant || !$driver) return;

        $load = Load::where('merchant_id', $merchant->id)->where('status', 'open')->latest()->first();
        if (!$load) return;

        $bid = Bid::where('load_id', $load->id)->where('driver_id', $driver->id)->first();
        if (!$bid) return;

        // 2. Merchant menerima bid dari driver Bambang
        $bid->update(['status' => 'accepted']);
        
        // Ubah status load menjadi assigned
        $load->update(['status' => 'assigned']);

        // 3. Buat Trip (Ini adalah triger untuk membuka jalur chat)
        $trip = Trip::create([
            'load_id' => $load->id,
            'driver_id' => $driver->id,
            'status' => 'assigned',
            'current_lat' => $load->origin_lat,
            'current_lng' => $load->origin_lng,
        ]);

        // 4. Buat histori Chat
        Message::create([
            'trip_id' => $trip->id,
            'sender_id' => $merchant->id,
            'receiver_id' => $driver->id,
            'message' => 'Halo Pak Bambang, bid sudah saya terima ya. Kapan bisa mulai muat?',
            'created_at' => Carbon::now()->subMinutes(15),
            'updated_at' => Carbon::now()->subMinutes(15),
            'is_read' => true
        ]);

        Message::create([
            'trip_id' => $trip->id,
            'sender_id' => $driver->id,
            'receiver_id' => $merchant->id,
            'message' => 'Siap Pak. Saya sedang meluncur ke lokasi penjemputan di Tanjung Priok. Estimasi 30 menit lagi sampai.',
            'created_at' => Carbon::now()->subMinutes(10),
            'updated_at' => Carbon::now()->subMinutes(10),
            'is_read' => true
        ]);

        Message::create([
            'trip_id' => $trip->id,
            'sender_id' => $merchant->id,
            'receiver_id' => $driver->id,
            'message' => 'Baik, tolong hubungi Pak Ahmad (081234567890) kalau sudah di gerbang ya. Nanti minta form Surat Jalan Elektroniknya sekalian.',
            'created_at' => Carbon::now()->subMinutes(5),
            'updated_at' => Carbon::now()->subMinutes(5),
            'is_read' => false
        ]);
        
        // Buat trip kedua yang sudah selesai untuk ngetes read-only chat (Closed Chat)
        $completedLoad = Load::create([
            'merchant_id' => $merchant->id,
            'title' => 'Pengiriman Sparepart (Selesai)',
            'item_name' => 'Sparepart Mesin',
            'type' => 'LTL',
            'total_weight' => 500,
            'weight_kg' => 500,
            'max_price' => 500000,
            'status' => 'completed',
            'bid_deadline' => Carbon::now()->subDays(3),
        ]);

        $completedTrip = Trip::create([
            'load_id' => $completedLoad->id,
            'driver_id' => $driver->id,
            'status' => 'completed',
            'created_at' => Carbon::now()->subDays(2),
            'updated_at' => Carbon::now()->subDays(2), // Selesai 2 hari yang lalu (Lebih dari 24 jam)
        ]);

        Message::create([
            'trip_id' => $completedTrip->id,
            'sender_id' => $driver->id,
            'receiver_id' => $merchant->id,
            'message' => 'Barang sudah sampai dengan aman ya pak. Terima kasih.',
            'created_at' => Carbon::now()->subDays(2)->addHours(2),
            'updated_at' => Carbon::now()->subDays(2)->addHours(2),
            'is_read' => true
        ]);
    }
}
