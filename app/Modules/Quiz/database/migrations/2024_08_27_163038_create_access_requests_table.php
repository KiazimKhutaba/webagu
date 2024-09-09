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
        Schema::create('access_requests', function (Blueprint $table) {
            $table->comment("user's access requests to specific resources: quizzes etc.");
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->boolean('granted')->default(false);
            $table->unsignedBigInteger('accessible_id');
            $table->string('accessible_type');
            $table->timestamps();

            $table->unique(['user_id', 'accessible_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_requests');
    }
};
