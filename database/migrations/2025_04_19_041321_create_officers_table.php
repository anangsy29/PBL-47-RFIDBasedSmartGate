<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficersTable extends Migration
{
    public function up()
    {
        Schema::create('officers', function (Blueprint $table) {
            $table->id(); // Kolom ID (Primary Key)
            $table->string('name'); // Nama officer
            $table->string('email')->unique(); // Email officer (harus unik)
            $table->string('password'); // Password untuk autentikasi
            $table->rememberToken(); // Kolom untuk remember me token
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('officers'); // Menghapus tabel jika rollback
    }
}
