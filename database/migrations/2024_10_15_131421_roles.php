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
        // Ensure the roles table is created
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); 
            $table->string('role_name');
            $table->integer('user_count')->default(0); 
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles'); // Drop the table if the migration is rolled back
    }
};
