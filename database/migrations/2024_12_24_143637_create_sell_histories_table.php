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
        Schema::create('sell_histories', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no');
            $table->decimal('hospital_price', 10, 2);
            $table->decimal('wholesale_price', 10, 2);
            $table->decimal('retail_price', 10, 2);
            $table->date('valid_from');
            $table->date('valid_to');
            $table->unsignedBigInteger('user_id');
            $table->enum('action', ['create', 'update', 'delete']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_histories');
    }
};
