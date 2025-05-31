<?php

namespace Database\Seeders;

use App\Models\Language;
use Database\Factories\LanguageFactory;
use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        (new Language)->truncate();

        LanguageFactory::new()->language('en', 'en', 'English')
            ->main()
            ->create();
    }
}
