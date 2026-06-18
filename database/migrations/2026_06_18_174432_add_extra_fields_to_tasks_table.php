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
            $table->time('task_time')->nullable()->after('due_date');
            $table->integer('estimated_time')->nullable()->after('task_time'); // En minutos
            $table->boolean('reminder')->default(false)->after('estimated_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['task_time', 'estimated_time', 'reminder']);
        });
    }
};
