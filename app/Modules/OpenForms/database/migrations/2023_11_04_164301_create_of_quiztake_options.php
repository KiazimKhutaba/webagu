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
        Schema::create('of_quiztake_options', function (Blueprint $table) {
            $table->id();
            $table->uuid('quiztake_id');
            $table->uuid('quiz_id');
            $table->uuid('question_id');
            $table->uuid('option_id')->nullable();
            $table->string('type');
            $table->json('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('of_quiztake_options');
    }
};
