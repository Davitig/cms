<?php

namespace Database\Seeders;

use Database\Factories\MenuFactory;

class MenuSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuFactory::new()->main()->title('Main Pages', 'List of main pages')->create();
    }
}
