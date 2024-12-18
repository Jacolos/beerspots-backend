<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('favorite_spots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('beer_spot_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Unikalny indeks zapobiegający duplikatom
            $table->unique(['user_id', 'beer_spot_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorite_spots');
    }
};
