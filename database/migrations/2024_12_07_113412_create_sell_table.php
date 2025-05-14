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
        Schema::create('sell', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('batch_no');
            $table->integer('hospital_price')->notNullable();
            $table->integer('wholesale_price')->notNullable();
            $table->integer('retail_price')->notNullable();
            $table->date('valid_from')->notNullable();
            $table->date('valid_to')->nullable();
            $table->date('deleted_at')->nullable();
            $table->integer('batch_id')->notNullable();
            $table->integer('no_of_units')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell');
    }
};
