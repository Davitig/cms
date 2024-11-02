<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsUserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cms_user_roles')->truncate();

        DB::table('cms_user_roles')->insert([
            [
                'role' => 'Administrator',
                'full_access' => 1
            ],
            [
                'role' => 'Member',
                'full_access' => 0
            ]
        ]);
    }
}
