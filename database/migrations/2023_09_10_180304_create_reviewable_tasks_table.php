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
        Schema::create('reviewable_tasks', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->enum('review_status', ['accepted', 'rejected', 'waiting_review', 'returned_for_revision'])->nullable()->default('waiting_review');
            $table->text('reviewer_reply')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_task_id');
            $table->unsignedBigInteger('lecture_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviewable_tasks');
    }
};
