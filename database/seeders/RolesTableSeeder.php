<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'role_name' => 'Admin',
                'user_count' => 0, 
                'status' => 'active',
            ],
            [
                'role_name' => 'Purchase User',
                'user_count' => 0, 
                'status' => 'active',
            ],
            [
                'role_name' => 'Sell user',
                'user_count' => 0,
                'status' => 'active',
            ],
            [
                'role_name' => 'Sub-Admin',
                'user_count' => 0,
                'status' => 'active',
            ],
        ]);
    }
}
