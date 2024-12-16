<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sell_carton', function (Blueprint $table) {
            $table->renameColumn('no_of_items', 'no_of_cartons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sell_carton', function (Blueprint $table) {
            $table->renameColumn('no_of_cartons', 'no_of_items');
        });
    }
};
