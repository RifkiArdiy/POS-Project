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
        Schema::create('m_user', function (Blueprint $table) {
            $table->id('user_id');
            $table->unsignedBigInteger('level_id')->index();//indexing untuk Foreign Key kolom level_id pada tabel m_level
            $table->string('username', 20)->unique();//menambahkan kolom username dengan tipe data varchar dengan panjang 20 karakter, menambahkan constraint unique agar tidak ada username yg sama
            $table->string('nama', 100);//menambahkan kolom nama dengan tipe data varchar dengan panjang 100 karakter
            $table->string('password');//menambahkan kolom password dengan tipe data varchar
            $table->timestamps();

            $table->foreign('level_id')->references('level_id')->on('m_level');//menambahkan foreign key level_id yang merujuk ke kolom level_id yang merupakan primary key pada tabel m_level
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_user');
    }
};
