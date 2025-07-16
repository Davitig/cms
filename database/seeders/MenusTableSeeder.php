<?php

namespace Database\Seeders;

use Database\Factories\MenuFactory;

class MenusTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuFactory::new()->main()->title('Main Pages', 'List of main pages')->create();
    }
}
