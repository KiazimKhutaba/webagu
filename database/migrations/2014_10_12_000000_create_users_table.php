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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            //$table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('firstname');
            $table->string('lastname');
            $table->string('department');
            $table->string('phone')->unique();
            $table->enum('role', ['user', 'admin'])->nullable()->default('user');
            $table->enum('status', ['not_activated', 'removed', 'banned', 'activated'])->default('not_activated')->nullable();
            $table->string('avatar')->nullable()->default('no_avatar.png');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
