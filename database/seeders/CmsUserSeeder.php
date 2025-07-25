<?php

namespace Database\Seeders;

use App\Models\CmsUser\CmsUserRole;
use Database\Factories\CmsUserFactory;
use Exception;

class CmsUserSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @throws \Exception
     */
    public function run(): void
    {
        $currentDate = date('Y-m-d H:i:s');

        $roleId = (new CmsUserRole)->fullAccess()->value('id');

        if (! $roleId) {
            throw new Exception('User role not set');
        }

        CmsUserFactory::new()->loginParams(
            $email = 'admin@example.com', $this->command->ask(
                'Enter password for cms user: ' . $email
            )
        )->fullName('Admin', 'Admin')->role($roleId)->create(['created_at' => $currentDate]);

        $roleId = (new CmsUserRole)->customAccess()->value('id');

        if (! $roleId) {
            throw new Exception('User role not set');
        }

        CmsUserFactory::new()->loginParams(
            $email = 'member@example.com', $this->command->ask(
                'Enter password for cms user: ' . $email
            )
        )->fullName('Member', 'Member')->role($roleId)->create(['created_at' => $currentDate]);
    }
}
