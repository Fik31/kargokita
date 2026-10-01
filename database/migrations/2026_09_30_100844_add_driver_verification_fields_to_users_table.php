<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_liveness_verified')->default(false)->after('tier');
            $table->boolean('is_bank_verified')->default(false)->after('is_liveness_verified');
            $table->string('bank_name')->nullable()->after('is_bank_verified');
            $table->string('bank_account_name')->nullable()->after('bank_name');
            $table->string('bank_account_number')->nullable()->after('bank_account_name');
            $table->boolean('is_sim_verified')->default(false)->after('bank_account_number');
            $table->boolean('is_stnk_verified')->default(false)->after('is_sim_verified');
            $table->boolean('is_kir_verified')->default(false)->after('is_stnk_verified');
            $table->boolean('is_sim_matched_with_vehicle')->default(false)->after('is_kir_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_liveness_verified',
                'is_bank_verified',
                'bank_name',
                'bank_account_name',
                'bank_account_number',
                'is_sim_verified',
                'is_stnk_verified',
                'is_kir_verified',
                'is_sim_matched_with_vehicle',
            ]);
        });
    }
};
