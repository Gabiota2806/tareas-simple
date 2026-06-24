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
            $table->index(['user_id', 'is_deleted'], 'tasks_user_id_is_deleted_index');
            $table->index(['user_id', 'subject_id'], 'tasks_user_id_subject_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('tasks_user_id_is_deleted_index');
            $table->dropIndex('tasks_user_id_subject_id_index');
        });
    }
};
