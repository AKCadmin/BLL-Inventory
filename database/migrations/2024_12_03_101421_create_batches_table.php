<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchesTable extends Migration
{
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();

            $table->string('batch_number')->unique()->notNullable();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('brand_id');
            $table->string('unit');
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->decimal('exchange_rate', 10, 4);
            $table->decimal('buy_price', 10, 2);
            $table->integer('no_of_units');
            $table->integer('quantity');
            $table->string('invoice_no');
            $table->date('date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('batches');
    }
}
