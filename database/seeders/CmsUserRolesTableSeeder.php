<?php

namespace Database\Seeders;

use App\Models\CmsUserRole;
use Database\Factories\CmsUserRoleFactory;
use Illuminate\Database\Seeder;

class CmsUserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        (new CmsUserRole)->truncate();

        CmsUserRoleFactory::new()->role('Administrator')->fullAccess()->create();

        CmsUserRoleFactory::new()->role('Member')->create();
    }
}
