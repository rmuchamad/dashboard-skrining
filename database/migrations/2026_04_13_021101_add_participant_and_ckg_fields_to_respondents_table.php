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
            $table->string('nik', 32)->nullable()->after('name');
            $table->date('birth_date')->nullable()->after('nik');
            $table->enum('participant_category', ['pjlp', 'non_asn', 'asn'])->nullable()->after('gender');
            $table->string('skpd')->nullable()->after('participant_category');
            $table->string('ukpd')->nullable()->after('skpd');
            $table->string('phone', 32)->nullable()->after('ukpd');
            $table->string('province')->nullable()->after('phone');
            $table->string('regency')->nullable()->after('province');
            $table->string('district')->nullable()->after('regency');
            $table->string('village')->nullable()->after('district');
            $table->text('address')->nullable()->after('village');
            $table->string('clinic_name')->nullable()->after('address');
            $table->string('ckg_location')->nullable()->after('clinic_name');
            $table->date('ckg_date')->nullable()->after('ckg_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('respondents', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'birth_date',
                'participant_category',
                'skpd',
                'ukpd',
                'phone',
                'province',
                'regency',
                'district',
                'village',
                'address',
                'clinic_name',
                'ckg_location',
                'ckg_date',
            ]);
        });
    }
};
