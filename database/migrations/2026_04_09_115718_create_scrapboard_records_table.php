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
        Schema::create('scrapboard_records', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('classification', ['A1', 'A2', 'A3', 'A4', 'A5']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scrapboard_records');
    }
};
