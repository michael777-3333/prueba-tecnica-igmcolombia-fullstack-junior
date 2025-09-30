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
        Schema::table('attached_files', function (Blueprint $table) {
            $table->string('file_type', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attached_files', function (Blueprint $table) {
            $table->enum('file_type', ['pdf','image','other'])->change();
        });
    }
};
