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
        Schema::table('assessment_criteria', function (Blueprint $table) {
            $table->string('evidence_minimum')->nullable()->after('is_mandatory');
            $table->string('pic_verification')->nullable()->after('evidence_minimum');
            $table->string('frequency')->nullable()->after('pic_verification');
            $table->string('notes')->nullable()->after('frequency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_criteria', function (Blueprint $table) {
            $table->dropColumn(['evidence_minimum', 'pic_verification', 'frequency', 'notes']);
        });
    }
};
