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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->string('name');
            $table->string('phone_number');
            $table->string('address');
            $table->decimal('credit_limit', 10, 2);
            $table->integer('payment_days');
            $table->string('type_of_customer');
            $table->boolean('sale_user_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
