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
        Schema::create('screening_questions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('text');
            $table->string('category'); // demografi, kanker_usus, tb, hati, mental, paru, merokok, aktivitas_fisik, reproduksi
            $table->string('dimension')->nullable(); // misal: status_perkawinan, batuk, aktivitas_rumah_tangga
            $table->string('type')->default('single_choice'); // single_choice, multiple_choice, numeric
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screening_questions');
    }
};
