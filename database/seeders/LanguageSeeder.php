<?php

namespace Database\Seeders;

use Database\Factories\LanguageFactory;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LanguageFactory::new()
            ->language('en', 'en', 'English')
            ->main()
            ->create();
    }
}
