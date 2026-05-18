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
        Schema::table('respondents', function (Blueprint $table) {
            $table->string('province_code', 20)->nullable()->after('province');
            $table->string('regency_code', 20)->nullable()->after('regency');
            $table->string('district_code', 20)->nullable()->after('district');
            $table->string('village_code', 20)->nullable()->after('village');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('respondents', function (Blueprint $table) {
            $table->dropColumn([
                'province_code',
                'regency_code',
                'district_code',
                'village_code',
            ]);
        });
    }
};
