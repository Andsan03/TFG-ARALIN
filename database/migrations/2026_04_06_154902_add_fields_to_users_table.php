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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['student', 'teacher', 'admin'])->default('student')->after('email');
            $table->string('profile_photo')->nullable()->after('role');
            $table->text('bio')->nullable()->after('profile_photo');
            $table->boolean('is_blocked')->default(false)->after('bio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'profile_photo', 'bio', 'is_blocked']);
        });
    }
};
