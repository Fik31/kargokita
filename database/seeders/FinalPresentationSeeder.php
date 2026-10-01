<?php

namespace Database\Seeders;

use App\Enums\AssessmentLevel;
use App\Enums\AssessmentStatus;
use App\Models\Assessment;
use App\Models\Bid;
use App\Models\Dispute;
use App\Models\Feedback;
use App\Models\Load;
use App\Models\Rating;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Waybill;
use App\Models\AssessmentItem;
use App\Models\AssessmentCriterion;
use App\Models\CorrectiveAction;
use App\Enums\ComplianceStatus;
use App\Enums\CaStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class FinalPresentationSeeder extends Seeder
{
    public function run()
    {
        // 1. Users Setup
        $admin = User::firstOrCreate(
            ['email' => 'admin@kargokita.com'],
            ['name' => 'Administrator', 'password' => Hash::make('password'), 'tier' => 'common']
        );
        if (!$admin->hasRole('administrator')) $admin->assignRole('administrator');

        $hse = User::firstOrCreate(
            ['email' => 'hse@kargokita.com'],
            ['name' => 'HSE Officer', 'password' => Hash::make('password'), 'tier' => 'common']
        );
        if (!$hse->hasRole('hse')) $hse->assignRole('hse');

        $driverDodi = User::firstOrCreate(
            ['email' => 'dodi@kargokita.com'],
            ['name' => 'Dodi Prakoso', 'password' => Hash::make('password'), 'tier' => 'gold']
        );
        if (!$driverDodi->hasRole('driver')) $driverDodi->assignRole('driver');

        $merchantSinar = User::firstOrCreate(
            ['email' => 'pt.sinar.jaya@kargokita.com'],
            ['name' => 'PT Sinar Jaya', 'password' => Hash::make('password'), 'tier' => 'premium', 'is_subscribed' => true]
        );
        if (!$merchantSinar->hasRole('merchant')) $merchantSinar->assignRole('merchant');

        $driverAdi = User::where('email', 'adi@kargokita.com')->first(); // Exists in RoleAndUserSeeder

        // Ensure Dodi has a vehicle for HSE
        $vehicleDodi = Vehicle::firstOrCreate(
            ['license_plate' => 'B 1234 CDD'],
            [
                'owner_id' => $driverDodi->id,
                'driver_id' => $driverDodi->id,
                'type' => 'CDD Box',
                'capacity_kg' => 4000,
            ]
        );

        // 2. Data for Dodi (Driver Gold) - TRIP PROCESS 1
        // Load assigned to Dodi, Trip status 'assigned'
        $loadDodi = Load::create([
            'merchant_id' => $merchantSinar->id,
            'type' => 'FTL',
            'title' => 'Pengiriman Sparepart Mesin Industri',
            'item_name' => 'Sparepart Mesin',
            'weight_kg' => 3000,
            'vehicle_type_needed' => 'CDD Box',
            'sender_name' => 'Gudang Sinar Jaya',
            'sender_phone' => '08111222333',
            'sender_address' => 'Kawasan Industri Cikarang',
            'receiver_name' => 'PT Manufaktur Maju',
            'receiver_phone' => '08999888777',
            'receiver_address' => 'Kawasan Industri Karawang',
            'max_price' => 1200000,
            'status' => 'closed',
            'escrow_status' => 'pending',
        ]);

        $bidDodi = Bid::create([
            'load_id' => $loadDodi->id,
            'driver_id' => $driverDodi->id,
            'amount' => 1150000,
            'status' => 'accepted',
        ]);

        $tripDodi = Trip::create([
            'driver_id' => $driverDodi->id,
            'load_id' => $loadDodi->id,
            'status' => 'loading', // Masuk proses muat sesuai instruksi user
        ]);

        // Trip History for Dodi (Completed)
        $loadDodiHistory = Load::create([
            'merchant_id' => $merchantSinar->id,
            'type' => 'FTL',
            'title' => 'Pengiriman Logistik Proyek (Selesai)',
            'item_name' => 'Besi Beton & Semen',
            'weight_kg' => 4000,
            'vehicle_type_needed' => 'CDD Box',
            'sender_name' => 'Gudang Material',
            'sender_phone' => '08123456789',
            'sender_address' => 'Cilegon',
            'receiver_name' => 'Proyek Gedung A',
            'receiver_phone' => '08987654321',
            'receiver_address' => 'Jakarta Selatan',
            'max_price' => 1500000,
            'status' => 'closed',
            'escrow_status' => 'released',
        ]);

        Bid::create([
            'load_id' => $loadDodiHistory->id,
            'driver_id' => $driverDodi->id,
            'amount' => 1450000,
            'status' => 'accepted',
        ]);

        $tripDodiHistory = Trip::create([
            'driver_id' => $driverDodi->id,
            'load_id' => $loadDodiHistory->id,
            'status' => 'completed',
        ]);
        
        \App\Models\TripEvent::create([
            'trip_id' => $tripDodiHistory->id,
            'type' => 'dwell',
            'location_name' => 'Rest Area KM 13',
            'notes' => 'Cek kondisi muatan berat.',
            'created_at' => Carbon::now()->subDays(1)->subHours(2),
        ]);
        
        \App\Models\TripPhoto::create([
            'trip_id' => $tripDodiHistory->id,
            'type' => 'load',
            'path' => 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
        ]);
        
        Waybill::create([
            'trip_id' => $tripDodiHistory->id,
            'status' => 'delivered',
        ]);
        
        Rating::create([
            'trip_id' => $tripDodiHistory->id,
            'rater_id' => $merchantSinar->id,
            'ratee_id' => $driverDodi->id,
            'score' => 5,
            'review' => 'Barang berat sampai dengan aman.',
        ]);

        // 3. HSE Data
        Assessment::create([
            'driver_id' => $driverDodi->id,
            'vehicle_id' => $vehicleDodi->id,
            'assessor_id' => $hse->id,
            'date' => Carbon::now()->subDays(2),
            'total_score' => 95.5,
            'has_mandatory_failure' => false,
            'status' => AssessmentStatus::VERIFIED,
            'level' => AssessmentLevel::GOLD,
        ]);

        // 4. More Merchant Data for Sinar Jaya & Admin Command Center

        // 4a. Load Open (Bidding stage)
        $loadOpen = Load::create([
            'merchant_id' => $merchantSinar->id,
            'type' => 'LTL',
            'title' => 'Distribusi Elektronik ke Retailer JKT',
            'item_name' => 'TV & AC',
            'weight_kg' => 1500,
            'vehicle_type_needed' => 'CDE Long',
            'sender_name' => 'Gudang Pusat',
            'sender_address' => 'Jakarta Timur',
            'receiver_name' => 'Toko Elektronik Makmur',
            'receiver_address' => 'Jakarta Barat',
            'max_price' => 800000,
            'status' => 'open',
        ]);

        // Urgent SOS Load
        $loadUrgent = Load::create([
            'merchant_id' => $merchantSinar->id,
            'type' => 'LTL',
            'title' => 'Pengiriman Darurat Alat Medis (SOS)',
            'item_name' => 'Alat Kesehatan & Oksigen',
            'weight_kg' => 800,
            'vehicle_type_needed' => 'Blind Van',
            'sender_name' => 'Gudang Farmasi Sinar',
            'sender_address' => 'Bekasi',
            'receiver_name' => 'RSUD Margono',
            'receiver_address' => 'Purwokerto',
            'max_price' => 2000000,
            'status' => 'open',
            'is_urgent' => true,
        ]);

        if ($driverAdi) {
            Bid::create([
                'load_id' => $loadOpen->id,
                'driver_id' => $driverAdi->id,
                'amount' => 750000,
                'status' => 'pending',
            ]);
            
            $vehicleAdi = Vehicle::firstOrCreate(
                ['license_plate' => 'D 9999 XA'],
                [
                    'owner_id' => $driverAdi->id,
                    'driver_id' => $driverAdi->id,
                    'type' => 'Pick Up',
                    'capacity_kg' => 1500,
                ]
            );
            Assessment::create([
                'driver_id' => $driverAdi->id,
                'vehicle_id' => $vehicleAdi->id,
                'assessor_id' => $hse->id,
                'date' => Carbon::now()->subDays(5),
                'total_score' => 75.0,
                'has_mandatory_failure' => false,
                'status' => AssessmentStatus::VERIFIED,
                'level' => AssessmentLevel::BRONZE,
            ]);
        }

        // Tambah 3 driver lain yang melakukan bid pada load yang sama
        $driverCandra = User::firstOrCreate(
            ['email' => 'candra.k@kargokita.com'],
            ['name' => 'Candra Kurniawan', 'password' => Hash::make('password'), 'tier' => 'gold']
        );
        if (!$driverCandra->hasRole('driver')) $driverCandra->assignRole('driver');
        Bid::create([
            'load_id' => $loadOpen->id,
            'driver_id' => $driverCandra->id,
            'amount' => 780000,
            'status' => 'pending',
        ]);

        $driverEko = User::firstOrCreate(
            ['email' => 'eko.p@kargokita.com'],
            ['name' => 'Eko Prasetyo', 'password' => Hash::make('password'), 'tier' => 'silver']
        );
        if (!$driverEko->hasRole('driver')) $driverEko->assignRole('driver');
        Bid::create([
            'load_id' => $loadOpen->id,
            'driver_id' => $driverEko->id,
            'amount' => 760000,
            'status' => 'pending',
        ]);

        $driverFajar = User::firstOrCreate(
            ['email' => 'fajar.n@kargokita.com'],
            ['name' => 'Fajar Nugroho', 'password' => Hash::make('password'), 'tier' => 'bronze']
        );
        if (!$driverFajar->hasRole('driver')) $driverFajar->assignRole('driver');
        Bid::create([
            'load_id' => $loadOpen->id,
            'driver_id' => $driverFajar->id,
            'amount' => 790000,
            'status' => 'pending',
        ]);

        // 4b. Load Completed with rating, feedback, and waybill
        $loadCompleted = Load::create([
            'merchant_id' => $merchantSinar->id,
            'type' => 'FTL',
            'title' => 'Distribusi Bahan Baku Tekstil',
            'item_name' => 'Benang dan Kain',
            'weight_kg' => 5000,
            'vehicle_type_needed' => 'Fuso Box',
            'sender_name' => 'Gudang Sinar Jaya',
            'sender_address' => 'Bandung',
            'receiver_name' => 'Pabrik Garmen Jaya',
            'receiver_address' => 'Semarang',
            'max_price' => 3500000,
            'status' => 'closed',
            'escrow_status' => 'released',
        ]);

        if ($driverAdi) {
            $tripCompleted = Trip::create([
                'driver_id' => $driverAdi->id,
                'load_id' => $loadCompleted->id,
                'status' => 'completed',
            ]);

            \App\Models\TripEvent::create([
                'trip_id' => $tripCompleted->id,
                'type' => 'dwell',
                'location_name' => 'Rest Area KM 57',
                'notes' => 'Berhenti untuk isi bahan bakar dan cek kondisi muatan.',
                'created_at' => Carbon::now()->subHours(10),
            ]);

            \App\Models\TripEvent::create([
                'trip_id' => $tripCompleted->id,
                'type' => 'deviation',
                'location_name' => 'Jalur Arteri Pantura',
                'notes' => 'Dialihkan dari tol karena ada kecelakaan di KM 100.',
                'created_at' => Carbon::now()->subHours(8),
            ]);

            \App\Models\TripPhoto::create([
                'trip_id' => $tripCompleted->id,
                'type' => 'load',
                'path' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
            ]);

            \App\Models\TripPhoto::create([
                'trip_id' => $tripCompleted->id,
                'type' => 'unload',
                'path' => 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
            ]);

            Waybill::create([
                'trip_id' => $tripCompleted->id,
                'status' => 'delivered',
            ]);

            Rating::create([
                'trip_id' => $tripCompleted->id,
                'rater_id' => $merchantSinar->id,
                'ratee_id' => $driverAdi->id,
                'score' => 5,
                'review' => 'Pengiriman sangat cepat dan aman. Mantap!',
            ]);

            Feedback::create([
                'trip_id' => $tripCompleted->id,
                'reviewer_id' => $merchantSinar->id,
                'reviewee_id' => $driverAdi->id,
                'role' => 'merchant',
                'partner_rating' => 5,
                'partner_feedback' => 'Driver sangat komunikatif.',
                'app_rating' => 5,
                'app_feedback' => 'Aplikasi KargoKita sangat membantu memonitor pengiriman.',
            ]);
        }

        // 4c. Load with Dispute
        $loadDispute = Load::create([
            'merchant_id' => $merchantSinar->id,
            'type' => 'FTL',
            'title' => 'Pengiriman Barang Pecah Belah',
            'item_name' => 'Keramik',
            'weight_kg' => 2000,
            'vehicle_type_needed' => 'CDD Box',
            'sender_name' => 'Sinar Jaya',
            'sender_address' => 'Bogor',
            'receiver_name' => 'Toko Bangunan Sentosa',
            'receiver_address' => 'Depok',
            'max_price' => 1000000,
            'status' => 'closed',
        ]);

        if ($driverAdi) {
            $tripDispute = Trip::create([
                'driver_id' => $driverAdi->id,
                'load_id' => $loadDispute->id,
                'status' => 'completed',
            ]);

            Dispute::create([
                'trip_id' => $tripDispute->id,
                'load_id' => $loadDispute->id,
                'reporter_id' => $merchantSinar->id,
                'type' => 'damage',
                'reason' => 'Beberapa dus keramik pecah saat dibongkar. Mohon investigasi dari admin.',
                'status' => 'open',
            ]);
        }

        // 5. Populate Dashboard HSE & Merchant Verifikasi Sample Data

        // Get some criteria to use
        $driverCriterion = AssessmentCriterion::where('target_role', 'driver')->first();
        $merchantCriterion = AssessmentCriterion::where('target_role', 'merchant')->first();

        if ($driverCriterion) {
            // Update Dodi's Assessment (Gold) with an AssessmentItem
            $assessmentDodi = Assessment::where('driver_id', $driverDodi->id)->first();
            if ($assessmentDodi) {
                AssessmentItem::create([
                    'assessment_id' => $assessmentDodi->id,
                    'criterion_id' => $driverCriterion->id,
                    'status' => ComplianceStatus::COMPLY,
                ]);
            }

            // Update Adi's Assessment (Bronze) with an AssessmentItem and an Open CA
            if ($driverAdi) {
                $assessmentAdi = Assessment::where('driver_id', $driverAdi->id)->first();
                if ($assessmentAdi) {
                    $itemAdi = AssessmentItem::create([
                        'assessment_id' => $assessmentAdi->id,
                        'criterion_id' => $driverCriterion->id,
                        'status' => ComplianceStatus::NON_COMPLY,
                    ]);

                    CorrectiveAction::create([
                        'assessment_item_id' => $itemAdi->id,
                        'notes' => 'Kendaraan kotor dan tidak terawat. Harap lakukan pembersihan dan servis.',
                        'pic_name' => 'Adi Setiawan',
                        'due_date' => Carbon::now()->addDays(3),
                        'status' => CaStatus::OPEN,
                    ]);
                }
            }

            // Create Pending Assessment for a Driver (Waiting List HSE)
            $driverBudi = User::firstOrCreate(
                ['email' => 'budi@kargokita.com'],
                ['name' => 'Budi Santoso', 'password' => Hash::make('password'), 'tier' => 'common']
            );
            if (!$driverBudi->hasRole('driver')) $driverBudi->assignRole('driver');

            // 1. Tambah Data Diri (VerificationRequest) yang diminta
            \App\Models\VerificationRequest::firstOrCreate(
                ['user_id' => $driverBudi->id, 'type' => 'driver', 'status' => 'pending'],
                ['data' => ['ktp' => '1234567890', 'sim' => '0987654321']]
            );

            $vehicleBudi = Vehicle::firstOrCreate(
                ['license_plate' => 'L 4567 OOO'],
                ['owner_id' => $driverBudi->id, 'driver_id' => $driverBudi->id, 'type' => 'CDD Box', 'capacity_kg' => 4000]
            );

            // 2. Gunakan firstOrCreate agar tidak double saat di-seed berulang
            $assessmentBudi = Assessment::firstOrCreate(
                ['driver_id' => $driverBudi->id, 'status' => AssessmentStatus::SUBMITTED],
                ['vehicle_id' => $vehicleBudi->id, 'date' => Carbon::now()]
            );

            AssessmentItem::firstOrCreate([
                'assessment_id' => $assessmentBudi->id,
                'criterion_id' => $driverCriterion->id,
            ], [
                'status' => ComplianceStatus::COMPLY,
            ]);
        }

        if ($merchantCriterion) {
            // Create Verified Assessment for Merchant Sinar Jaya (Dashboard Merchant)
            $assessmentSinar = Assessment::create([
                'driver_id' => $merchantSinar->id,
                'assessor_id' => $admin->id,
                'date' => Carbon::now()->subDays(10),
                'total_score' => 92.5,
                'status' => AssessmentStatus::VERIFIED,
                'level' => AssessmentLevel::GOLD,
            ]);

            AssessmentItem::create([
                'assessment_id' => $assessmentSinar->id,
                'criterion_id' => $merchantCriterion->id,
                'status' => ComplianceStatus::COMPLY,
            ]);

            // Create Pending Assessment for a Merchant (Waiting List Admin)
            $merchantBaru = User::firstOrCreate(
                ['email' => 'toko.baru@kargokita.com'],
                ['name' => 'Toko Bangunan Baru', 'password' => Hash::make('password'), 'tier' => 'common']
            );
            if (!$merchantBaru->hasRole('merchant')) $merchantBaru->assignRole('merchant');

            $assessmentMerchantBaru = Assessment::create([
                'driver_id' => $merchantBaru->id,
                'date' => Carbon::now()->subHours(2),
                'status' => AssessmentStatus::SUBMITTED,
            ]);

            AssessmentItem::create([
                'assessment_id' => $assessmentMerchantBaru->id,
                'criterion_id' => $merchantCriterion->id,
                'status' => ComplianceStatus::COMPLY,
            ]);
        }
    }
}
