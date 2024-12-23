<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PagesSeeder extends Seeder
{
    public function run()
    {
        // List of page names
        $pages = [
            'Dashboard',
            'Role Manager',
            'Permission Manager',
            'User Management',
            'Company',
            'Product',
            'Stock List',
            'Purchase',
            'Sell',
            'Sell Counter',
            'Order',
            'Invoice'
        ];

        // Loop through the pages and create each one in the database
        foreach ($pages as $page) {
            Page::create([
                'name' => $page,
                'slug' => strtolower(str_replace(' ', '-', $page)), // Converts spaces to hyphens for the slug
            ]);
        }
    }
}
