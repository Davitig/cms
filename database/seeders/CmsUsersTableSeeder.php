<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

class CmsUsersTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentDate = date('Y-m-d H:i:s');

        DB::table('cms_users')->truncate();

        DB::table('cms_users')->insert([
            [
                'email' => 'admin@example.com',
                'cms_user_role_id' => 1,
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'blocked' => 0,
                'password' => '$2y$10$SGapfDy0uRJPxGD/KV0BaeW5YiP4tNN2kSFkEtvSA1P1t0AUX51oq', // 123456
                'created_at' => $currentDate
            ],
            [
                'email' => 'test@example.com',
                'cms_user_role_id' => 2,
                'first_name' => 'Test',
                'last_name' => 'Test',
                'blocked' => 0,
                'password' => '$2y$10$SGapfDy0uRJPxGD/KV0BaeW5YiP4tNN2kSFkEtvSA1P1t0AUX51oq', // 123456
                'created_at' => $currentDate
            ]
        ]);

        DB::table('cms_settings')->truncate();

        DB::table('cms_settings')->insert([
            [
                'cms_user_id' => 1,
                'created_at' => $currentDate
            ]
        ]);
    }
}
