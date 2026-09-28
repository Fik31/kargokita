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
        Schema::create('loads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained('users')->cascadeOnDelete();
            $table->string('type'); // LTL, FTL
            $table->decimal('total_weight', 8, 2)->nullable();
            $table->decimal('available_weight', 8, 2)->nullable();
            $table->decimal('max_price', 15, 2);
            $table->string('status')->default('open'); // open, closed, in_transit, done
            $table->decimal('origin_lat', 10, 8)->nullable();
            $table->decimal('origin_lng', 11, 8)->nullable();
            $table->decimal('dest_lat', 10, 8)->nullable();
            $table->decimal('dest_lng', 11, 8)->nullable();
            $table->longText('route_polyline')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loads');
    }
};
