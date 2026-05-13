<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Unifica modalidad: online, presencial, ambas (sustituye presential/mixed en MySQL y datos legacy).
     */
    public function up(): void
    {
        $this->normalizeData();

        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE classes MODIFY COLUMN modality ENUM('online','presencial','ambas') NOT NULL");
        DB::statement("ALTER TABLE search_histories MODIFY COLUMN modality ENUM('online','presencial','ambas') NULL");
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            $this->revertDataStrings();

            return;
        }

        $this->revertDataStrings();

        DB::statement("ALTER TABLE classes MODIFY COLUMN modality ENUM('online','presential','mixed') NOT NULL");
        DB::statement("ALTER TABLE search_histories MODIFY COLUMN modality ENUM('online','presential','mixed') NULL");
    }

    private function normalizeData(): void
    {
        foreach (['classes', 'search_histories'] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            if (! Schema::hasColumn($table, 'modality')) {
                continue;
            }

            DB::table($table)->where('modality', 'presential')->update(['modality' => 'presencial']);
            DB::table($table)->where('modality', 'mixed')->update(['modality' => 'ambas']);
        }

        if (Schema::hasTable('bookings') && Schema::hasColumn('bookings', 'booking_modality')) {
            DB::table('bookings')->where('booking_modality', 'presential')->update(['booking_modality' => 'presencial']);
            // "mixed"/"ambas" no son modalidades de sesión válidas; forzar a online (videollamada) por defecto
            DB::table('bookings')->whereIn('booking_modality', ['mixed', 'ambas'])->update(['booking_modality' => 'online']);
        }
    }

    private function revertDataStrings(): void
    {
        foreach (['classes', 'search_histories'] as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'modality')) {
                continue;
            }
            DB::table($table)->where('modality', 'ambas')->update(['modality' => 'mixed']);
            DB::table($table)->where('modality', 'presencial')->update(['modality' => 'presential']);
        }

        if (Schema::hasTable('bookings') && Schema::hasColumn('bookings', 'booking_modality')) {
            DB::table('bookings')->where('booking_modality', 'ambas')->update(['booking_modality' => 'mixed']);
            DB::table('bookings')->where('booking_modality', 'presencial')->update(['booking_modality' => 'presential']);
        }
    }
};
