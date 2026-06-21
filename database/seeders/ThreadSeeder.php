<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThreadSeeder extends Seeder
{
    public function run(): void
    {
        $threads = [
            ['bigger_than' => 0, 'threads' => 50, 'status' => 'active'],
            ['bigger_than' => 1, 'threads' => 100, 'status' => 'active'],
            ['bigger_than' => 10, 'threads' => 500, 'status' => 'active'],
            ['bigger_than' => 50, 'threads' => 1000, 'status' => 'active'],
            ['bigger_than' => 100, 'threads' => 2000, 'status' => 'active'],
        ];

        DB::table('threads')->truncate();

        DB::table('threads')->insert($threads);
    }
}
