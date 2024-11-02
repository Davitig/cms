<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

class MenusTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menus')->truncate();

        DB::table('menus')->insert([
            [
                'main' => 1,
                'title' => 'Main Pages',
                'description' => 'List of main pages',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }
}
