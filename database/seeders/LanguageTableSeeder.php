<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('languages')->truncate();

        DB::table('languages')->insert([
            [
                'language' => 'en',
                'position' => 1,
                'short_name' => 'en',
                'full_name' => 'English'
            ],
            [
                'language' => 'ka',
                'position' => 2,
                'short_name' => 'ge',
                'full_name' => 'Georgian'
            ]
        ]);
    }
}
