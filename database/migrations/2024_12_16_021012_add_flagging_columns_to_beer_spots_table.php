<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beer_spots', function (Blueprint $table) {
            $table->boolean('flagged_for_review')->default(false)->after('status');
            $table->timestamp('flagged_at')->nullable()->after('flagged_for_review');
            $table->unsignedBigInteger('flagged_by')->nullable()->after('flagged_at');
            
            $table->foreign('flagged_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('beer_spots', function (Blueprint $table) {
            $table->dropForeign(['flagged_by']);
            $table->dropColumn(['flagged_for_review', 'flagged_at', 'flagged_by']);
        });
    }
};