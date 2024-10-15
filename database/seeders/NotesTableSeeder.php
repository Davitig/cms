<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

class NotesTableSeeder extends DatabaseSeeder
{
    /**
     * Run notes table seeder.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('notes')->truncate();

        DB::table('notes')->insert([
            [
                'title'       => 'Title',
                'description' => 'Short description',
                'content'     => 'Full content description',
                'created_at'  => date('Y-m-d H:i:s')
            ]
        ]);
    }
}
