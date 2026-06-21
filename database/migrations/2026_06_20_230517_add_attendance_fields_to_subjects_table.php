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
        Schema::table('subjects', function (Blueprint $table) {
            $table->boolean('track_attendance')->default(false)->after('color_code');
            $table->integer('max_absences')->nullable()->after('track_attendance');
            $table->integer('absences_count')->default(0)->after('max_absences');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['track_attendance', 'max_absences', 'absences_count']);
        });
    }
};
