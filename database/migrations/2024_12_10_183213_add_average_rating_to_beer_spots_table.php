<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('beer_spots', function (Blueprint $table) {
            $table->decimal('average_rating', 3, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('beer_spots', function (Blueprint $table) {
            $table->dropColumn('average_rating');
        });
    }
};
