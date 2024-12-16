<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Insert multiple records into the test table
        DB::table('test')->insert([
            [
                'customers' => 'John Doe',
                'accommodation' => 'Hotel Deluxe',
                'check_in_date' => Carbon::parse('2024-09-01'),
                'check_out_date' => Carbon::parse('2024-09-07'),
                'billing_amount' => 350.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customers' => 'Jane Smith',
                'accommodation' => 'Beach Resort',
                'check_in_date' => Carbon::parse('2024-09-05'),
                'check_out_date' => Carbon::parse('2024-09-10'),
                'billing_amount' => 450.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more records as needed
        ]);
    }
}
