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
        Schema::create('image_metas', function (Blueprint $table) {
            $table->id();
            $table->string('generated_name');
            $table->unsignedBigInteger('user_id')->comment('image owner');
            $table->string('dest')->comment('avatar or another type');
            $table->json('exif')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_metas');
    }
};
