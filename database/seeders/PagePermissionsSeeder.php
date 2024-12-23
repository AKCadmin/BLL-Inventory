<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PagePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = ['View', 'Add', 'Edit', 'Delete'];

        foreach ($permissions as $permission) {
            \App\Models\PagePermission::create(['name' => $permission]);
        }
    }
}
