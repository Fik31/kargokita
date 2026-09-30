<?php

namespace Database\Seeders;

use App\Models\Load;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        Role::firstOrCreate(['name' => 'administrator']);
        Role::firstOrCreate(['name' => 'merchant']);
        Role::firstOrCreate(['name' => 'driver']);
        Role::firstOrCreate(['name' => 'hse']);

        // Create Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@kargokita.com'],
            ['name' => 'Administrator', 'password' => Hash::make('password'), 'tier' => 'common']
        );
        $admin->assignRole('administrator');

        // Create HSE user
        $hse = User::firstOrCreate(
            ['email' => 'hse@kargokita.com'],
            ['name' => 'HSE Officer', 'password' => Hash::make('password'), 'tier' => 'common']
        );
        $hse->assignRole('hse');

        // Create Dummy Merchants
        $merchantKosong = User::firstOrCreate(
            ['email' => 'budi.merchant@kargokita.com'],
            ['name' => 'Toko Sembako Budi', 'password' => Hash::make('password'), 'tier' => 'basic']
        );
        $merchantKosong->assignRole('merchant');

        $merchantTrusted = User::firstOrCreate(
            ['email' => 'maju.logistik@kargokita.com'],
            ['name' => 'PT. Maju Logistik', 'password' => Hash::make('password'), 'tier' => 'trusted']
        );
        $merchantTrusted->assignRole('merchant');

        $merchantDeposit = User::firstOrCreate(
            ['email' => 'makmur.abadi@kargokita.com'],
            ['name' => 'CV. Makmur Abadi', 'password' => Hash::make('password'), 'tier' => 'verified', 'is_subscribed' => true]
        );
        $merchantDeposit->assignRole('merchant');

        // Create Dummy Drivers
        $driverKosong = User::firstOrCreate(
            ['email' => 'adi@kargokita.com'],
            ['name' => 'Adi Setiawan', 'password' => Hash::make('password'), 'tier' => 'bronze']
        );
        $driverKosong->assignRole('driver');

        $driverGold = User::firstOrCreate(
            ['email' => 'dodi@kargokita.com'],
            ['name' => 'Dodi Prakoso', 'password' => Hash::make('password'), 'tier' => 'gold']
        );
        $driverGold->assignRole('driver');

        $driverDeposit = User::firstOrCreate(
            ['email' => 'bambang@kargokita.com'],
            ['name' => 'Bambang Pamungkas', 'password' => Hash::make('password'), 'tier' => 'silver', 'is_subscribed' => true]
        );
        $driverDeposit->assignRole('driver');

        // Create Dummy Posts for Feed
        if (Post::count() == 0) {
            Post::create([
                'user_id' => $driverGold->id,
                'content' => 'Lagi kosong nih di area Tanjung Priok, ada muatan arah Bandung?',
                'type' => 'seeking_load',
            ]);

            Post::create([
                'user_id' => $merchantTrusted->id,
                'content' => 'Butuh armada CDD FTL untuk besok pagi jam 8. Muatan ringan (kerupuk). Ada yang standby sekitar Tangerang?',
                'type' => 'seeking_driver',
            ]);

            Post::create([
                'user_id' => $driverGold->id,
                'content' => 'Hati-hati lur tol cipularang KM 90 ada perbaikan jalan, macet panjang.',
                'type' => 'status',
            ]);

            Post::create([
                'user_id' => $merchantTrusted->id,
                'content' => 'Sedia muatan rutin tiap rabu dari Surabaya ke Semarang. Silakan driver yang minat merapat atau chat.',
                'type' => 'status',
            ]);
        }

        // Create Dummy Loads
        if (Load::count() == 0) {
            Load::create([
                'merchant_id' => $merchantTrusted->id,
                'type' => 'LTL',
                'total_weight' => 4.0,
                'available_weight' => 1.5,
                'max_price' => 5950000,
                'status' => 'open',
                'origin_lat' => -6.175110,
                'origin_lng' => 106.865036, // Jakarta
                'dest_lat' => -7.250445,
                'dest_lng' => 112.768845, // Surabaya
                'drop_points' => json_encode([
                    ['name' => 'Cirebon (KM 207)', 'lat' => -6.7320, 'lng' => 108.5523],
                    ['name' => 'Semarang (KM 429)', 'lat' => -6.9932, 'lng' => 110.4203],
                ]),
            ]);

            Load::create([
                'merchant_id' => $merchantTrusted->id,
                'type' => 'FTL',
                'total_weight' => 8.0,
                'available_weight' => 0,
                'max_price' => 8900000,
                'status' => 'open',
                'origin_lat' => -6.914744,
                'origin_lng' => 107.609810, // Bandung
                'dest_lat' => -7.250445,
                'dest_lng' => 112.768845, // Surabaya
                'drop_points' => json_encode([
                    ['name' => 'Sumedang (Cisumdawu)', 'lat' => -6.8395, 'lng' => 107.9272],
                ]),
            ]);
        }
    }
}
