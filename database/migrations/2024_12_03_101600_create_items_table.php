<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carton_id')->constrained('cartons')->cascadeOnDelete();
            $table->string('item_serial_number')->unique()->notNullable();
            $table->enum('status', ['in_stock', 'damaged', 'confiscated'])->default('in_stock');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}
