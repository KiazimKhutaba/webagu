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
        Schema::create('of_question_options', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('question_id');
            $table->foreign('question_id')->references('id')->on('of_questions')->onDelete('cascade');
            $table->text('text')->nullable();
            $table->boolean('is_checked')->default(false);
            $table->string('control_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('of_question_options');
    }
};
