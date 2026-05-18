<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Repara MySQL cuando el ENUM de modality sigue siendo el legacy
     * ('online','presential','mixed'): no se puede escribir 'presencial'/'ambas'
     * hasta ampliar el ENUM. Idempotente si ya está en el estado final.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasTable('classes') && Schema::hasColumn('classes', 'modality')) {
            DB::statement("ALTER TABLE classes MODIFY COLUMN modality ENUM('online','presential','mixed','presencial','ambas') NOT NULL");
            DB::table('classes')->where('modality', 'presential')->update(['modality' => 'presencial']);
            DB::table('classes')->where('modality', 'mixed')->update(['modality' => 'ambas']);
            DB::statement("ALTER TABLE classes MODIFY COLUMN modality ENUM('online','presencial','ambas') NOT NULL");
        }

        if (Schema::hasTable('search_histories') && Schema::hasColumn('search_histories', 'modality')) {
            DB::statement("ALTER TABLE search_histories MODIFY COLUMN modality ENUM('online','presential','mixed','presencial','ambas') NULL");
            DB::table('search_histories')->where('modality', 'presential')->update(['modality' => 'presencial']);
            DB::table('search_histories')->where('modality', 'mixed')->update(['modality' => 'ambas']);
            DB::statement("ALTER TABLE search_histories MODIFY COLUMN modality ENUM('online','presencial','ambas') NULL");
        }

        if (Schema::hasTable('bookings') && Schema::hasColumn('bookings', 'booking_modality')) {
            DB::table('bookings')->where('booking_modality', 'presential')->update(['booking_modality' => 'presencial']);
            DB::table('bookings')->whereIn('booking_modality', ['mixed', 'ambas'])->update(['booking_modality' => 'online']);
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasTable('classes') && Schema::hasColumn('classes', 'modality')) {
            DB::statement("ALTER TABLE classes MODIFY COLUMN modality ENUM('online','presential','mixed','presencial','ambas') NOT NULL");
            DB::table('classes')->where('modality', 'ambas')->update(['modality' => 'mixed']);
            DB::table('classes')->where('modality', 'presencial')->update(['modality' => 'presential']);
            DB::statement("ALTER TABLE classes MODIFY COLUMN modality ENUM('online','presential','mixed') NOT NULL");
        }

        if (Schema::hasTable('search_histories') && Schema::hasColumn('search_histories', 'modality')) {
            DB::statement("ALTER TABLE search_histories MODIFY COLUMN modality ENUM('online','presential','mixed','presencial','ambas') NULL");
            DB::table('search_histories')->where('modality', 'ambas')->update(['modality' => 'mixed']);
            DB::table('search_histories')->where('modality', 'presencial')->update(['modality' => 'presential']);
            DB::statement("ALTER TABLE search_histories MODIFY COLUMN modality ENUM('online','presential','mixed') NULL");
        }
    }
};
