<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->nullable()->constrained('assessments')->nullOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->date('date');
            $table->string('odometer')->nullable();
            $table->string('maintenance_type'); // preventive, corrective, inspection
            $table->string('component');
            $table->date('due_date')->nullable();
            $table->date('actual_date')->nullable();
            $table->string('status')->default('OPEN');
            $table->string('wo_invoice')->nullable();
            $table->text('finding')->nullable(); // Temuan
            $table->text('corrective_action')->nullable();
            $table->string('pic')->nullable();
            $table->string('evidence_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_records');
    }
};
