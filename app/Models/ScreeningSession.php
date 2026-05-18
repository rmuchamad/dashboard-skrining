<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScreeningSession extends Model
{
    protected $fillable = [
        'respondent_id',
        'ticket_number',
        'screened_at',
        'risk_score',
        'risk_category',
        'attendance_status',
        'service_status',
        'report_sent_at',
            'service_notes',
    ];

    protected function casts(): array
    {
        return [
            'screened_at' => 'datetime',
            'report_sent_at' => 'datetime',
            'service_notes' => 'array',
        ];
    }

    public function respondent()
    {
        return $this->belongsTo(Respondent::class);
    }

    public function screeningAnswers()
    {
        return $this->hasMany(ScreeningAnswer::class);
    }
}
