<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

class CmsUsersTableSeeder extends DatabaseSeeder
{
    /**
     * Run cms_users table seeder.
     */
    public function run(): void
    {
        $currentDate = date('Y-m-d H:i:s');

        DB::table('cms_users')->truncate();

        DB::table('cms_users')->insert([
            [
                'email' => 'admin@example.com',
                'first_name' => 'admin',
                'last_name' => 'Admin',
                'role' => 'admin',
                'blocked' => 0,
                'password' => '$2y$10$SGapfDy0uRJPxGD/KV0BaeW5YiP4tNN2kSFkEtvSA1P1t0AUX51oq', // 123456
                'created_at' => $currentDate
            ]
        ]);

        DB::table('cms_settings')->truncate();

        DB::table('cms_settings')->insert([
            [
                'cms_user_id' => 1,
                'created_at'  => $currentDate
            ]
        ]);
    }
}
