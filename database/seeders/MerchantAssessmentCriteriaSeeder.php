<?php

namespace Database\Seeders;

use App\Models\AssessmentCriterion;
use Illuminate\Database\Seeder;

class MerchantAssessmentCriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $criteria = [
            ['code' => 'M01', 'target_role' => 'merchant', 'category' => 'LEGAL IDENTITY', 'sub_category' => 'Legal', 'name' => 'NIB/Legal entity, NPWP, nama perusahaan, alamat', 'weight' => 20.0, 'is_mandatory' => true, 'evidence_minimum' => 'Dokumen Legal', 'pic_verification' => 'Admin Merchant', 'frequency' => 'Sekali', 'notes' => 'Kritis'],
            ['code' => 'M02', 'target_role' => 'merchant', 'category' => 'PIC VERIFICATION', 'sub_category' => 'Identitas', 'name' => 'KTP/PIC, nomor HP, email, OTP', 'weight' => 10.0, 'is_mandatory' => true, 'evidence_minimum' => 'KTP/ID', 'pic_verification' => 'Admin Merchant', 'frequency' => 'Sekali', 'notes' => null],
            ['code' => 'M03', 'target_role' => 'merchant', 'category' => 'BUSINESS PROFILE', 'sub_category' => 'Profil', 'name' => 'Model Bisnis', 'weight' => 10.0, 'is_mandatory' => true, 'evidence_minimum' => 'Deskripsi', 'pic_verification' => 'Admin Merchant', 'frequency' => 'Tahunan', 'notes' => null],
            ['code' => 'M04', 'target_role' => 'merchant', 'category' => 'BUSINESS PROFILE', 'sub_category' => 'Profil', 'name' => 'Lama Usaha', 'weight' => 10.0, 'is_mandatory' => true, 'evidence_minimum' => 'Dokumen Legal/Bukti', 'pic_verification' => 'Admin Merchant', 'frequency' => 'Tahunan', 'notes' => null],
            ['code' => 'M05', 'target_role' => 'merchant', 'category' => 'BUSINESS PROFILE', 'sub_category' => 'Volume', 'name' => 'Volume Pengiriman', 'weight' => 20.0, 'is_mandatory' => true, 'evidence_minimum' => 'Data Historis', 'pic_verification' => 'Admin Merchant', 'frequency' => 'Bulanan', 'notes' => null],
            ['code' => 'M06', 'target_role' => 'merchant', 'category' => 'PAYMENT READINESS', 'sub_category' => 'Keuangan', 'name' => 'Metode pembayaran, rekening, willingness/ability to pay', 'weight' => 10.0, 'is_mandatory' => true, 'evidence_minimum' => 'Rekening/Mutasi', 'pic_verification' => 'Finance / Admin', 'frequency' => 'Tahunan', 'notes' => null],
            ['code' => 'M07', 'target_role' => 'merchant', 'category' => 'OPERATIONAL READINESS', 'sub_category' => 'Operasional', 'name' => 'Lokasi pickup, jam operasional, kesiapan loading, contact person', 'weight' => 5.0, 'is_mandatory' => true, 'evidence_minimum' => 'Foto Lokasi/Dokumen', 'pic_verification' => 'Admin Merchant', 'frequency' => 'Tahunan', 'notes' => null],
            ['code' => 'M08', 'target_role' => 'merchant', 'category' => 'RISK SCREENING', 'sub_category' => 'Risiko', 'name' => 'Duplicate account, blacklist, fraud indicator, dokumen mismatch', 'weight' => 15.0, 'is_mandatory' => true, 'evidence_minimum' => 'Pengecekan Sistem', 'pic_verification' => 'Risk / Admin', 'frequency' => 'Monitoring', 'notes' => 'Kritis'],
        ];

        foreach ($criteria as $criterion) {
            AssessmentCriterion::updateOrCreate(
                ['code' => $criterion['code']],
                $criterion
            );
        }
    }
}
