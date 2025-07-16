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
        $this->call(LanguageTableSeeder::class);

        $this->call(CmsUserRolesTableSeeder::class);

        $this->call(CmsUsersTableSeeder::class);

        $this->call(MenusTableSeeder::class);
    }
}
