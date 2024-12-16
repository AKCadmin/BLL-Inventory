<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('customers'); // Customer details (use string or another suitable type)
            $table->string('accommodation'); // Accommodation details
            $table->date('check_in_date'); // Check-in date
            $table->date('check_out_date'); // Check-out date
            $table->decimal('billing_amount', 8, 2); // Billing amount with precision
            $table->timestamps(); // Adds created_at and updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test');
    }
}
