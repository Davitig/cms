<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $this->call(CmsUsersTableSeeder::class);

        $this->call(MenusTableSeeder::class);

        $this->call(NotesTableSeeder::class);

        Schema::enableForeignKeyConstraints();
    }
}