<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellPricesTable extends Migration
{
    public function up()
    {
        Schema::create('sell_prices', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('batch_no');
            $table->integer('hospital')->notNullable();
            $table->integer('wholesaler')->notNullable();
            $table->integer('retailer')->notNullable();
            $table->date('valid_from')->notNullable();
            $table->date('valid_to')->nullable();
            $table->date('deleted_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sell_prices');
    }
}
