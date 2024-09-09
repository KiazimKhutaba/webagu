<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE `questions` CHANGE `type` `type` ENUM('question','task','quiz') DEFAULT 'question';
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
