<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('attendance_id')->constrained()->cascadeOnDelete();
            $table->date("presence_date");
            $table->time("presence_enter_time");
            $table->time("presence_out_time")->nullable();
            $table->string('qr_code')->nullable(); // Column to store scanned QR code
            $table->boolean('is_late')->default(false); // Add this line to your presences migration


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
