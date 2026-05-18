<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 50)->default('nakes')->after('email');
        });

        Schema::table('screening_sessions', function (Blueprint $table) {
            $table->json('service_notes')->nullable()->after('report_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('screening_sessions', function (Blueprint $table) {
            $table->dropColumn(['service_notes']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role']);
        });
    }
};
