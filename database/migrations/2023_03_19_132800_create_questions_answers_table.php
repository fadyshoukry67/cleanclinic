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
        Schema::create('questions_answers', function (Blueprint $table) {
            $table->foreignId('User-id')->references('id')->on('Users');
            $table->foreignId('question_id')->references('id')->on('questions');
            $table->string('answer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions_answers');
    }
};
