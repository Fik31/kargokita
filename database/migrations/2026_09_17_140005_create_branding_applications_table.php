<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branding_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pengajuan', 'ditinjau', 'diterima', 'dicairkan', 'terkirim', 'ditolak'])->default('pengajuan');
            $table->string('proof_image')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamp('deadline')->nullable(); // 7 days from applied_at for proof
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branding_applications');
    }
};
