<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartonsTable extends Migration
{
    public function up()
    {
        Schema::create('cartons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->string('carton_number')->unique()->notNullable();
            $table->integer('total_items')->nullable();
            $table->integer('no_of_items_inside')->notNullable();
            $table->integer('missing_items')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cartons');
    }
}
