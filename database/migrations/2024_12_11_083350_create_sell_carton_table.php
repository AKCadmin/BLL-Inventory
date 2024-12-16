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
        Schema::create('sell_carton', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('carton_id');
            $table->integer('no_of_items');
            $table->integer('no_of_items_sell');
            $table->integer('sell_id');
            $table->integer('order_id');
            $table->timestamps();

            $table->foreign('carton_id')->references('id')->on('cartons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_carton');
    }
};
