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
        Schema::create('of_quizzes', function (Blueprint $table) {
            $table->uuid('id');
            $table->index('id');
            $table->text('title');
            $table->unsignedBigInteger('lecture_id');
            $table->foreign('lecture_id')->references('id')->on('lectures')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->integer('duration');
            $table->boolean('is_available')->default(0);
            $table->boolean('shuffle_questions')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('of_quizzes');
    }
};
