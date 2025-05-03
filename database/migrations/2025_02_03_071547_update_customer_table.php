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
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('retail_shop')->default(0)->after('sale_user_status');
            $table->decimal('opening_balance', 10, 2)->default(0.00)->after('retail_shop');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('retail_shop');
            $table->dropColumn('opening_balance');
        });
    }
};
