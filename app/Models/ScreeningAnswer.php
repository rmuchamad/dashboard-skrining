<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScreeningAnswer extends Model
{
    protected $fillable = [
        'screening_session_id',
        'screening_question_id',
        'screening_option_id',
        'text_answer',
        'score',
    ];

    public function screeningSession(): BelongsTo
    {
        return $this->belongsTo(ScreeningSession::class);
    }

    public function screeningQuestion(): BelongsTo
    {
        return $this->belongsTo(ScreeningQuestion::class);
    }

    public function screeningOption(): BelongsTo
    {
        return $this->belongsTo(ScreeningOption::class);
    }
}
