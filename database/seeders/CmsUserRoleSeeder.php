<?php

namespace Database\Seeders;

use Database\Factories\CmsUserRoleFactory;
use Illuminate\Database\Seeder;

class CmsUserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CmsUserRoleFactory::new()->role('Administrator')->fullAccess()->create();

        CmsUserRoleFactory::new()->role('Member')->customAccess()->create();
    }
}
