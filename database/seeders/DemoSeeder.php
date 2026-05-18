<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        $this->truncateDemoTables();
        Schema::enableForeignKeyConstraints();

        $this->call([
            UserSeeder::class,
            ClassSeeder::class,
            BookingSeeder::class,
            ReviewSeeder::class,
            FavoriteSeeder::class,
            SearchHistorySeeder::class,
            AssessmentSeeder::class,
            NewQuestionSeeder::class,
            MatematicasQuestionSeeder::class,
        ]);
    }

    private function truncateDemoTables(): void
    {
        $tables = [
            'reviews',
            'bookings',
            'favorites',
            'search_histories',
            'assessments',
            'classes',
            'questions',
            'users',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }
    }
}
