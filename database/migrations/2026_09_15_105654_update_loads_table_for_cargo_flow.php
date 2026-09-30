<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loads', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('item_name')->nullable();
            $table->integer('weight_kg')->nullable();
            $table->integer('koli')->nullable();
            $table->string('vehicle_type_needed')->nullable();

            $table->string('sender_name')->nullable();
            $table->string('sender_phone')->nullable();
            $table->text('sender_address')->nullable();

            $table->string('receiver_name')->nullable();
            $table->string('receiver_phone')->nullable();
            $table->text('receiver_address')->nullable();

            $table->decimal('distance', 8, 2)->nullable();

            $table->dateTime('bid_deadline')->nullable();

            $table->string('escrow_status')->default('pending'); // pending, held, released
            $table->boolean('is_paylater')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('loads', function (Blueprint $table) {
            $table->dropColumn([
                'title', 'item_name', 'weight_kg', 'koli', 'vehicle_type_needed',
                'sender_name', 'sender_phone', 'sender_address',
                'receiver_name', 'receiver_phone', 'receiver_address',
                'distance', 'bid_deadline', 'escrow_status', 'is_paylater',
            ]);
        });
    }
};
