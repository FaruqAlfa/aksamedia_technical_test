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
        Schema::create('devisions', function (Blueprint $table) {
            $table->id();
            $table->string('Mobile apps');
            $table->string('QA');
            $table->string('Full Stack');
            $table->string('Backend');
            $table->string('Frontend');
            $table->string('UI/UX Designer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devisions');
    }
};
