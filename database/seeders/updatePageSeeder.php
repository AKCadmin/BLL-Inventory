<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;

class updatePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // List of page names
        $pages = [
            'Organization',
            'Customer',
            'Purchase History',
            'Sale History'
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
