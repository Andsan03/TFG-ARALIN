<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->index(['is_active', 'category'], 'classes_active_category_index');
            $table->index(['modality', 'level'], 'classes_modality_level_index');
            $table->index('teacher_id', 'classes_teacher_id_index');
            $table->index('price_per_hour', 'classes_price_index');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->index(['student_id', 'status'], 'bookings_student_status_index');
            $table->index(['class_id', 'status'], 'bookings_class_status_index');
            $table->index('scheduled_at', 'bookings_scheduled_at_index');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index('teacher_id', 'reviews_teacher_id_index');
            $table->index('rating', 'reviews_rating_index');
        });

        Schema::table('assessments', function (Blueprint $table) {
            $table->index(['student_id', 'subject'], 'assessments_student_subject_index');
        });

        Schema::table('search_histories', function (Blueprint $table) {
            $table->index(['student_id', 'created_at'], 'search_histories_student_created_index');
        });

        Schema::table('favorites', function (Blueprint $table) {
            $table->index('teacher_id', 'favorites_teacher_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropIndex('classes_active_category_index');
            $table->dropIndex('classes_modality_level_index');
            $table->dropIndex('classes_teacher_id_index');
            $table->dropIndex('classes_price_index');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('bookings_student_status_index');
            $table->dropIndex('bookings_class_status_index');
            $table->dropIndex('bookings_scheduled_at_index');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_teacher_id_index');
            $table->dropIndex('reviews_rating_index');
        });

        Schema::table('assessments', function (Blueprint $table) {
            $table->dropIndex('assessments_student_subject_index');
        });

        Schema::table('search_histories', function (Blueprint $table) {
            $table->dropIndex('search_histories_student_created_index');
        });

        Schema::table('favorites', function (Blueprint $table) {
            $table->dropIndex('favorites_teacher_id_index');
        });
    }
};
