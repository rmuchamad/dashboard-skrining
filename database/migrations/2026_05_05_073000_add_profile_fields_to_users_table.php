<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 32)->nullable()->unique()->after('name');
            $table->string('jabatan')->nullable()->after('role');
            $table->string('instansi')->nullable()->after('jabatan');
            $table->string('whatsapp', 32)->nullable()->after('instansi');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nik', 'jabatan', 'instansi', 'whatsapp']);
        });
    }
};
