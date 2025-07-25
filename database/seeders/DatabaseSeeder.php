<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(LanguageSeeder::class);

        $this->call(CmsUserRoleSeeder::class);

        $this->call(CmsUserSeeder::class);

        $this->call(MenuSeeder::class);
    }
}
