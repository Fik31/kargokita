<?php

namespace Database\Seeders;

use App\Models\Bid;
use App\Models\Load;
use App\Models\Trip;
use App\Models\User;
use App\Notifications\TripCompletedEscrowNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class CargoFlowDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Merchant User (Premium Tier agar bisa pakai Hak Eksklusif)
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

        // 2. Driver Users (Berbagai Tier)
        $driverC = User::updateOrCreate(
            ['email' => 'candra.gunawan@kargokita.com'],
            ['name' => 'Candra Gunawan', 'password' => Hash::make('password'), 'tier' => 'gold', 'is_subscribed' => true]
        );
        if (! $driverC->hasRole('driver')) {
            $driverC->assignRole('driver');
        }

        $driverB = User::updateOrCreate(
            ['email' => 'bambang.suryadi@kargokita.com'],
            ['name' => 'Bambang Suryadi', 'password' => Hash::make('password'), 'tier' => 'silver', 'is_subscribed' => true]
        );
        if (! $driverB->hasRole('driver')) {
            $driverB->assignRole('driver');
        }

        // 3. Load 1: Open Bidding (Menggunakan Cargo Fee, Min Driver Tier: silver)
        $loadOpen = Load::create([
            'merchant_id' => $merchant->id,
            'type' => 'FTL',
            'title' => 'Distribusi Sembako Jabodetabek (Mendesak)',
            'item_name' => 'Beras dan Minyak Goreng',
            'weight_kg' => 4500,
            'vehicle_type_needed' => 'CDD',
            'sender_name' => 'Gudang Pusat Sinar Jaya',
            'sender_phone' => '08111222333',
            'sender_address' => 'Kawasan Industri Pulogadung, Jakarta',
            'sender_notes' => 'Harap bawa terpal cadangan, cuaca mendung.',
            'receiver_name' => 'Agen Berkah Sembako',
            'receiver_phone' => '08999888777',
            'receiver_address' => 'Pasar Induk Kramat Jati',
            'receiver_notes' => 'Langsung ke blok C3, tanya Pak Joko.',
            'max_price' => 1500000,
            'is_paylater' => true,
            'min_driver_tier' => 'silver',
            'status' => 'open',
            'escrow_status' => 'pending',
        ]);

        Bid::create([
            'load_id' => $loadOpen->id,
            'driver_id' => $driverB->id,
            'amount' => 1400000,
            'status' => 'pending',
        ]);

        // 4. Load 2: Selesai Perjalanan (Trip Completed), Menunggu Pencairan Escrow
        $loadCompleted = Load::create([
            'merchant_id' => $merchant->id,
            'type' => 'FTL',
            'title' => 'Pengiriman Elektronik Cikarang ke Bandung',
            'item_name' => 'TV LED dan Kulkas',
            'weight_kg' => 2000,
            'vehicle_type_needed' => 'CDE Long',
            'sender_name' => 'PT Maju Bersama',
            'sender_phone' => '08123456789',
            'sender_address' => 'Cikarang Industrial Estate',
            'sender_notes' => 'Barang fragile, handling dengan hati-hati.',
            'receiver_name' => 'Toko Elektronik Makmur',
            'receiver_phone' => '08887777666',
            'receiver_address' => 'Asia Afrika, Bandung',
            'receiver_notes' => 'Bongkar di pintu belakang gudang.',
            'max_price' => 2500000,
            'is_paylater' => true,
            'status' => 'closed', // Cargo selesai diproses di Bidding
            'escrow_status' => 'pending', // Tapi uangnya masih ditahan sistem
        ]);

        $bidAccepted = Bid::create([
            'load_id' => $loadCompleted->id,
            'driver_id' => $driverC->id,
            'amount' => 2400000,
            'status' => 'accepted',
        ]);

        $trip = Trip::create([
            'driver_id' => $driverC->id,
            'load_id' => $loadCompleted->id,
            'status' => 'completed',
        ]);

        // Simulasikan notifikasi dikirim ke admin
        $admins = User::role('administrator')->get();
        if ($admins->count() > 0) {
            Notification::send($admins, new TripCompletedEscrowNotification($trip));
        }

        $this->command->info('Data sample presentasi (Paylater, Notes, Escrow Notification) berhasil digenerate.');
    }
}
