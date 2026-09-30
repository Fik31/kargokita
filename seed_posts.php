<?php

use App\Models\Post;
use App\Models\User;

$merchant = User::role('merchant')->first();
$driver = User::role('driver')->first();

if ($merchant) {
    Post::create([
        'user_id' => $merchant->id,
        'content' => "Info muatan! Butuh 2 armada Fuso Box untuk angkut sparepart motor dari Jakarta ke Surabaya minggu depan. Yang ready bisa langsung japri atau comment ya. Aman dan proses bongkar muat cepat dijamin! 👍",
        'type' => 'seeking_driver',
        'image' => 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
    ]);
}

if ($driver) {
    Post::create([
        'user_id' => $driver->id,
        'content' => "Alhamdulillah bongkaran beres tepat waktu. Siap meluncur cari muatan arah balik ke Jakarta. Armada sehat, supir semangat! 💪",
        'type' => 'seeking_load',
        'image' => 'https://images.unsplash.com/photo-1519003722824-194d4455a60c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
    ]);
}

echo "Dummy posts with images added successfully!\n";
