<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            // Add new columns for moderation
            $table->timestamp('moderated_at')->nullable()->after('admin_notes');
            $table->foreignId('moderated_by')->nullable()->after('moderated_at')
                ->constrained('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['moderated_by']);
            $table->dropColumn(['moderated_at', 'moderated_by']);
        });
    }
};