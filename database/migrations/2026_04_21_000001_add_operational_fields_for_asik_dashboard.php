<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('respondents', function (Blueprint $table) {
            $table->string('guardian_phone', 32)->nullable()->after('phone');
        });

        Schema::table('screening_sessions', function (Blueprint $table) {
            $table->string('ticket_number', 7)->nullable()->unique()->after('respondent_id');
            $table->enum('attendance_status', ['belum_hadir', 'sudah_hadir'])->default('belum_hadir')->after('risk_category');
            $table->enum('service_status', ['belum_diperiksa', 'sedang_diperiksa', 'selesai_pemeriksaan'])->default('belum_diperiksa')->after('attendance_status');
        });
    }

    public function down(): void
    {
        Schema::table('screening_sessions', function (Blueprint $table) {
            $table->dropColumn(['ticket_number', 'attendance_status', 'service_status']);
        });

        Schema::table('respondents', function (Blueprint $table) {
            $table->dropColumn(['guardian_phone']);
        });
    }
};
