<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('beer_spot_id')->constrained()->onDelete('cascade');
            $table->decimal('rating', 2, 1);  // Ocena od 0.0 do 5.0
            $table->text('comment')->nullable();
            $table->date('visit_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            
            // Użytkownik może dodać tylko jedną opinię dla danego miejsca
            $table->unique(['user_id', 'beer_spot_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }

};
