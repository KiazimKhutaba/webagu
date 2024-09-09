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
        Schema::create('of_questions', function (Blueprint $table) {
            // Todo: saving uuid as string have performance issues
            // https://www.mysqltutorial.org/mysql-uuid/
            $table->uuid('id');
            $table->index('id');
            $table->uuid('quiz_id');
            $table->foreign('quiz_id')->references('id')->on('of_quizzes')->onDelete('cascade');
            $table->text('title');
            $table->string('image')->nullable();
            $table->string('variant_type')->default('radio');
            $table->boolean('has_description')->default(0);
            $table->text('description')->nullable();
            $table->unsignedInteger('points')->default(5);
            $table->boolean('required')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('of_questions');
    }
};
