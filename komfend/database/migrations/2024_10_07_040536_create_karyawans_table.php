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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('photo');
            $table->string('email')->nullable(); 
            $table->string('tanggal_lahir');
            $table->string('phone');
            $table->string('qrcode')->nullable();
            $table->enum('status', ['aktif', 'tidak aktif']);
            $table->string('jabatan'); 
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
