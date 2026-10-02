<?php
use App\Models\User;
use App\Models\Trip;
use App\Models\Load;
use App\Models\Message;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

// IDs based on previous check
$adminId = 1;
$merchantId = 3;
$driverId = 6;

DB::beginTransaction();
try {
    // 1. Chat normal antara merchant dan driver (Sedang dalam perjalanan)
    $load1 = Load::forceCreate([
        'merchant_id' => $merchantId,
        'title' => 'Pengiriman Elektronik ke Bandung',
        'type' => 'Elektronik',
        'total_weight' => 2000,
        'available_weight' => 0,
        'max_price' => 1500000,
        'status' => 'assigned',
        'origin_lat' => -6.2,
        'origin_lng' => 106.8,
        'dest_lat' => -6.9,
        'dest_lng' => 107.6,
        'sender_address' => 'Jakarta',
        'receiver_address' => 'Bandung',
    ]);

    $trip1 = Trip::forceCreate([
        'load_id' => $load1->id,
        'driver_id' => $driverId,
        'status' => 'in_transit',
        'admin_assistance_requested' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Messages for Trip 1
    Message::forceCreate(['trip_id' => $trip1->id, 'sender_id' => $merchantId, 'receiver_id' => $driverId, 'message' => 'Halo Pak Supir, apakah barang sudah dimuat?', 'created_at' => Carbon::now()->subMinutes(30), 'updated_at' => Carbon::now()->subMinutes(30), 'is_read' => true]);
    Message::forceCreate(['trip_id' => $trip1->id, 'sender_id' => $driverId, 'receiver_id' => $merchantId, 'message' => 'Sudah Pak, ini sedang jalan ke Bandung.', 'created_at' => Carbon::now()->subMinutes(25), 'updated_at' => Carbon::now()->subMinutes(25), 'is_read' => true]);
    Message::forceCreate(['trip_id' => $trip1->id, 'sender_id' => $merchantId, 'receiver_id' => $driverId, 'message' => 'Oke, hati-hati di jalan ya.', 'created_at' => Carbon::now()->subMinutes(20), 'updated_at' => Carbon::now()->subMinutes(20), 'is_read' => false]);

    // 2. Chat normal yang sudah selesai antar mengantar
    $load2 = Load::forceCreate([
        'merchant_id' => $merchantId,
        'title' => 'Pengiriman Makanan Beku ke Surabaya',
        'type' => 'Makanan',
        'total_weight' => 5000,
        'available_weight' => 0,
        'max_price' => 3500000,
        'status' => 'completed',
        'origin_lat' => -6.9,
        'origin_lng' => 110.4,
        'dest_lat' => -7.2,
        'dest_lng' => 112.7,
        'sender_address' => 'Semarang',
        'receiver_address' => 'Surabaya',
    ]);

    $trip2 = Trip::forceCreate([
        'load_id' => $load2->id,
        'driver_id' => $driverId,
        'status' => 'completed',
        'admin_assistance_requested' => false,
        'created_at' => now()->subDays(1),
        'updated_at' => now()->subHours(2),
    ]);

    // Messages for Trip 2
    Message::forceCreate(['trip_id' => $trip2->id, 'sender_id' => $driverId, 'receiver_id' => $merchantId, 'message' => 'Pak, barang sudah sampai di gudang Surabaya dan sudah di cek.', 'created_at' => Carbon::now()->subHours(3), 'updated_at' => Carbon::now()->subHours(3), 'is_read' => true]);
    Message::forceCreate(['trip_id' => $trip2->id, 'sender_id' => $merchantId, 'receiver_id' => $driverId, 'message' => 'Baik, terima kasih banyak kerjasamanya.', 'created_at' => Carbon::now()->subHours(2)->subMinutes(50), 'updated_at' => Carbon::now()->subHours(2)->subMinutes(50), 'is_read' => true]);
    Message::forceCreate(['trip_id' => $trip2->id, 'sender_id' => $driverId, 'receiver_id' => $merchantId, 'message' => 'Sama-sama Pak, sampai jumpa di pengiriman selanjutnya.', 'created_at' => Carbon::now()->subHours(2)->subMinutes(40), 'updated_at' => Carbon::now()->subHours(2)->subMinutes(40), 'is_read' => true]);

    // 3. Chat yang minta bantuan admin (Driver dan Merchant ribut)
    $load3 = Load::forceCreate([
        'merchant_id' => $merchantId,
        'title' => 'Pengiriman Bahan Bangunan ke Jogja',
        'type' => 'Bahan Bangunan',
        'total_weight' => 8000,
        'available_weight' => 0,
        'max_price' => 4500000,
        'status' => 'assigned',
        'origin_lat' => -6.2,
        'origin_lng' => 106.8,
        'dest_lat' => -7.7,
        'dest_lng' => 110.3,
        'sender_address' => 'Jakarta',
        'receiver_address' => 'Yogyakarta',
    ]);

    $trip3 = Trip::forceCreate([
        'load_id' => $load3->id,
        'driver_id' => $driverId,
        'status' => 'in_transit',
        'admin_assistance_requested' => true,
        'created_at' => now()->subDays(1),
        'updated_at' => now(),
    ]);

    // Messages for Trip 3
    Message::forceCreate(['trip_id' => $trip3->id, 'sender_id' => $merchantId, 'receiver_id' => $driverId, 'message' => 'Pak, kenapa barangnya nyasar ke Solo? Kan tujuannya Jogja!', 'created_at' => Carbon::now()->subMinutes(60), 'updated_at' => Carbon::now()->subMinutes(60), 'is_read' => true]);
    Message::forceCreate(['trip_id' => $trip3->id, 'sender_id' => $driverId, 'receiver_id' => $merchantId, 'message' => 'Jalannya dialihkan Pak karena ada perbaikan, saya cuma ikuti rute yang aman.', 'created_at' => Carbon::now()->subMinutes(55), 'updated_at' => Carbon::now()->subMinutes(55), 'is_read' => true]);
    Message::forceCreate(['trip_id' => $trip3->id, 'sender_id' => $merchantId, 'receiver_id' => $driverId, 'message' => 'Alasan aja kamu! Bensin abis buat muter-muter kan? Saya potong ongkosnya!', 'created_at' => Carbon::now()->subMinutes(50), 'updated_at' => Carbon::now()->subMinutes(50), 'is_read' => true]);
    Message::forceCreate(['trip_id' => $trip3->id, 'sender_id' => $driverId, 'receiver_id' => $merchantId, 'message' => 'Loh jangan sembarangan potong Pak! Sesuai kesepakatan awal!', 'created_at' => Carbon::now()->subMinutes(45), 'updated_at' => Carbon::now()->subMinutes(45), 'is_read' => true]);
    Message::forceCreate(['trip_id' => $trip3->id, 'sender_id' => $merchantId, 'receiver_id' => $driverId, 'message' => 'Pokoknya saya lapor admin!', 'created_at' => Carbon::now()->subMinutes(40), 'updated_at' => Carbon::now()->subMinutes(40), 'is_read' => true]);
    
    // Admin intervenes
    Message::forceCreate(['trip_id' => $trip3->id, 'sender_id' => $adminId, 'receiver_id' => $merchantId, 'message' => 'Halo Bapak-bapak, harap tenang. Kami dari tim KargoKita sedang mengecek rute GPS dan menengahi masalah ini. Tolong jangan ambil keputusan sepihak.', 'created_at' => Carbon::now()->subMinutes(5), 'updated_at' => Carbon::now()->subMinutes(5), 'is_read' => false]);

    DB::commit();
    echo "Sample data for Chat created successfully!\n";

} catch (\Exception $e) {
    DB::rollback();
    echo "Error: " . $e->getMessage() . "\n";
}
