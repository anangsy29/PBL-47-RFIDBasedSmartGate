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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('vehicles_id');
            $table->unsignedBigInteger('user_id');

            $table->string('plate_number')->unique();
            $table->enum('vehicle_type', ['Mobil', 'Motor']);
            $table->string('brand')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();

            // Foreign key mengacu ke kolom user_id pada tabel users
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
