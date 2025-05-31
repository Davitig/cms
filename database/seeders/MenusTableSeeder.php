<?php

namespace Database\Seeders;

use App\Models\Menu;
use Database\Factories\MenuFactory;

class MenusTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        (new Menu)->truncate();

        MenuFactory::new()->main()->title('Main Pages', 'List of main pages')->create();
    }
}
