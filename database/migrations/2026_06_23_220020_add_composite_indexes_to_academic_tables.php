<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('universities', function (Blueprint $table) {
            $table->index(['user_id', 'name'], 'universities_user_id_name_index');
        });

        Schema::table('careers', function (Blueprint $table) {
            $table->index(['university_id', 'name'], 'careers_university_id_name_index');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->index(['user_id', 'is_active'], 'subjects_user_id_is_active_index');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->index(['user_id', 'is_deleted'], 'tasks_user_id_is_deleted_index');
            $table->index(['user_id', 'subject_id'], 'tasks_user_id_subject_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('universities', function (Blueprint $table) {
            $table->dropIndex('universities_user_id_name_index');
        });

        Schema::table('careers', function (Blueprint $table) {
            $table->dropIndex('careers_university_id_name_index');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropIndex('subjects_user_id_is_active_index');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('tasks_user_id_is_deleted_index');
            $table->dropIndex('tasks_user_id_subject_id_index');
        });
    }
};