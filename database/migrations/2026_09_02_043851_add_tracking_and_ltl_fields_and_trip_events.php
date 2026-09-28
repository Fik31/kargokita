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
        Schema::table('loads', function (Blueprint $table) {
            $table->json('drop_points')->nullable()->after('dest_lng');
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->integer('admin_radius_m')->default(100)->after('current_lng');
            $table->boolean('is_deviated')->default(false)->after('admin_radius_m');
            $table->timestamp('last_moved_at')->nullable()->after('is_deviated');
        });

        Schema::create('trip_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // e.g. 'dwell', 'deviation', 'status_change'
            $table->string('location_name')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_events');

        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn(['admin_radius_m', 'is_deviated', 'last_moved_at']);
        });

        Schema::table('loads', function (Blueprint $table) {
            $table->dropColumn('drop_points');
        });
    }
};
