<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respondent extends Model
{
    protected $fillable = [
        'name',
        'nik',
        'birth_date',
        'gender',
        'participant_category',
        'skpd',
        'ukpd',
        'phone',
        'guardian_phone',
        'guardian_name',
        'province',
        'province_code',
        'regency',
        'regency_code',
        'district',
        'district_code',
        'village',
        'village_code',
        'address',
        'clinic_name',
        'ckg_location',
        'ckg_date',
        'age',
        'marital_status',
        'has_marriage_plan',
        'is_disabled',
        'is_pregnant',
        'work_unit',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'ckg_date' => 'date',
            'has_marriage_plan' => 'boolean',
            'is_disabled' => 'boolean',
            'is_pregnant' => 'boolean',
        ];
    }

    public function screeningSessions()
    {
        return $this->hasMany(ScreeningSession::class);
    }
}
