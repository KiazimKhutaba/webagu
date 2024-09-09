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
        Schema::table('files', function (Blueprint $table) {
            $table->renameColumn('object_source', 'fileable_type');
            $table->renameColumn('object_source_id', 'fileable_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fileable_type_and_fileable_id', function (Blueprint $table) {
            $table->renameColumn('fileable_type', 'object_source');
            $table->renameColumn('fileable_id', 'object_source_id');
        });
    }
};
