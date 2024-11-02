<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->call(LanguageTableSeeder::class);

        $this->call(CmsUserRolesTableSeeder::class);

        $this->call(CmsUsersTableSeeder::class);

        $this->call(MenusTableSeeder::class);

        Schema::enableForeignKeyConstraints();
    }
}
