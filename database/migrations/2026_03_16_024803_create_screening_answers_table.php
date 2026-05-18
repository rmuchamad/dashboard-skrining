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
        Schema::create('screening_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('screening_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('screening_question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('screening_option_id')->nullable()->constrained()->nullOnDelete();
            $table->string('text_answer')->nullable();
            $table->unsignedInteger('score')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screening_answers');
    }
};
