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
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('team_members')->nullable()->after('reminder');
            $table->string('submission_format')->nullable()->after('team_members');
            $table->decimal('grade', 4, 2)->nullable()->after('submission_format');
            $table->date('enrollment_date')->nullable()->after('grade');
            $table->string('exam_type')->nullable()->after('enrollment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['team_members', 'submission_format', 'grade', 'enrollment_date', 'exam_type']);
        });
    }
};
