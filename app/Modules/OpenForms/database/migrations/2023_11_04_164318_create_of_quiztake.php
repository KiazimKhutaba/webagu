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
        Schema::create('of_quiztake', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('quiz_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('started_at')->nullable();
            $table->ipAddress('client_ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->unsignedInteger('page_switches_count')->default(0);
            $table->json('geolocation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('of_quiztake');
    }
};
