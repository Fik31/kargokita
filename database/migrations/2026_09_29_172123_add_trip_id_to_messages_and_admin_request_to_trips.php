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
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('trip_id')->nullable()->after('id')->constrained('trips')->onDelete('cascade');
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->boolean('admin_assistance_requested')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['trip_id']);
            $table->dropColumn('trip_id');
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('admin_assistance_requested');
        });
    }
};
