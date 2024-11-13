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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('img');
            $table->longText('description');
            $table->integer('max_person');
            $table->decimal('price_monday_to_thursday', 10, 2);
            $table->decimal('price_friday_to_sunday', 10, 2);
            $table->decimal('guarantee', 10, 2);
            $table->decimal('cleaning', 10, 2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
