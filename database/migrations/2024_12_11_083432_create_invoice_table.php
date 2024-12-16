<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sell_id');
            $table->string('customer_name');
            $table->string('customer_type');
            $table->string('invoice_number');
            $table->integer('order_id');
            $table->boolean('invoice_approved');
            $table->timestamps();

            $table->foreign('sell_id')->references('id')->on('sell_counter')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
