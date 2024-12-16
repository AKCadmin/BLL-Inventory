<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id(); // ID column (Primary key)
            $table->unsignedBigInteger('role_id'); // role_id column
             $table->string('permission_name'); // Permission Name column
            //$table->unsignedBigInteger('module_id'); // module_id column
            $table->enum('status', ['0', '1', '2'])->default('0'); // status column
            $table->timestamps(); // created_at and updated_at columns
            $table->timestamp('deleted_at')->nullable(); // deleted_at column (nullable for soft deletes)

            // Optionally, you can add foreign keys if these columns reference other tables
            //  $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            //  $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
