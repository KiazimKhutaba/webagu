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
        Schema::table('task_reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('lecture_id');
            $table->foreign('lecture_id')->references('id')->on('lectures');//->onDelete('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_reviews', function (Blueprint $table) {
            $table->dropForeign('lecture_id');
        });
    }
};
