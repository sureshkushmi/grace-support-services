<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'Admin', 'description' => 'Full access to all records and reports.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Manager', 'description' => 'Manages staff and reviews reports.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Worker', 'description' => 'Field staff who submit progress reports.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
