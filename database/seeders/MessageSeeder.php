<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::role('administrator')->first();
        $merchant = User::role('merchant')->first();
        $driver = User::role('driver')->first();

        $budi = User::where('name', 'Budi Tejo')->first();
        if (! $budi) {
            $budi = User::create([
                'name' => 'Budi Tejo',
                'email' => 'budi.tejo@example.com',
                'password' => bcrypt('password'),
            ]);
            $budi->assignRole('driver');
        }

        if (! $admin || ! $merchant || ! $driver) {
            return;
        }

        // Budi Tejo (1)
        Message::create([
            'sender_id' => $admin->id,
            'receiver_id' => $budi->id,
            'message' => 'Tolong segera merapat ke gudang ya Pak Budi.',
            'created_at' => Carbon::now()->subMinutes(10),
        ]);
        Message::create([
            'sender_id' => $budi->id,
            'receiver_id' => $admin->id,
            'message' => 'Oke Pak, saya berangkat.',
            'created_at' => Carbon::now()->subMinutes(5),
        ]);

        // Merchant (2)
        Message::create([
            'sender_id' => $merchant->id,
            'receiver_id' => $admin->id,
            'message' => 'Tolong update posisi ya.',
            'created_at' => Carbon::now()->subHours(2),
            'is_read' => false,
        ]);

        Message::create([
            'sender_id' => $merchant->id,
            'receiver_id' => $admin->id,
            'message' => 'Apakah sudah sampai?',
            'created_at' => Carbon::now()->subHours(1),
            'is_read' => false,
        ]);

        // Anto (3)
        Message::create([
            'sender_id' => $driver->id,
            'receiver_id' => $admin->id,
            'message' => 'Siap laksanakan.',
            'created_at' => Carbon::now()->subDays(1),
        ]);

        echo "Message dummy data seeded.\n";
    }
}
