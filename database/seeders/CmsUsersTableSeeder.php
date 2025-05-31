<?php

namespace Database\Seeders;

use App\Models\CmsUser;
use App\Models\CmsUserRole;
use Database\Factories\CmsUserFactory;
use Illuminate\Support\Facades\DB;

class CmsUsersTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentDate = date('Y-m-d H:i:s');

        (new CmsUser)->truncate();

        CmsUserFactory::new()->loginParams(
            $email = 'admin@example.com', $this->command->ask(
                'Enter password for cms user: ' . $email
            )
        )->fullName('Admin', 'Admin')->role(
            (new CmsUserRole)->fullAccess()->value('id')
        )->create(['created_at' => $currentDate]);

        CmsUserFactory::new()->loginParams(
            $email = 'member@example.com', $this->command->ask(
                'Enter password for cms user: ' . $email
            )
        )->fullName('Member', 'Member')->role(
            (new CmsUserRole)->customAccess()->value('id')
        )->create(['created_at' => $currentDate]);

        DB::table('cms_settings')->truncate();

        DB::table('cms_settings')->insert([
            [
                'cms_user_id' => 1,
                'created_at' => $currentDate
            ]
        ]);
    }
}
