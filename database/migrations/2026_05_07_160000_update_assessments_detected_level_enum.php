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
        Schema::table('assessments', function (Blueprint $table) {
            // Cambiar el ENUM a los valores correctos
            $table->enum('detected_level', ['principiante', 'intermedio', 'avanzado'])
                ->default('principiante')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            // Revertir al ENUM anterior
            $table->enum('detected_level', ['primaria', 'eso', 'bachillerato', 'universidad'])
                ->default('primaria')
                ->change();
        });
    }
};
