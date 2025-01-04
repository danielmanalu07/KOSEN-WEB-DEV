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
        Schema::create('absensi_karyawans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_karyawan');
            $table->foreign('id_karyawan')->references('id')->on('karyawans')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('id_absensi');
            $table->foreign('id_absensi')->references('id')->on('absensis')->onDelete('cascade')->onUpdate('cascade');
            $table->dateTime('checkIn')->nullable();
            $table->dateTime('checkOut')->nullable();
            $table->enum('status', ['Hadir', 'Terlambat']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_karyawans');
    }
};
