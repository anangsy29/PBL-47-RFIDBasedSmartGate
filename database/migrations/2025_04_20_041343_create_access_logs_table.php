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
        Schema::create('access_logs', function (Blueprint $table) {
            $table->bigIncrements('log_id');
            $table->unsignedBigInteger('tags_id');
            $table->timestamp('accessed_at')->default(now());
            $table->enum('status', ['Allowed', 'Denied']);
            $table->string('note')->nullable(); // misal untuk alasan ditolak
            $table->timestamps();


            $table->foreign('tags_id')->references('tags_id')->on('rfid_tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_logs');
    }
};
