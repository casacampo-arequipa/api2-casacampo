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
        Schema::create('cottage_reservation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cottage_id');
            $table->unsignedBigInteger('reservation_id');
            $table->foreign('cottage_id')->references('id')->on('cottages')->onDelete('cascade');
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cottage_reservation');
    }
};