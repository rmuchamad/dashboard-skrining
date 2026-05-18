<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScreeningOption extends Model
{
    protected $fillable = [
        'screening_question_id',
        'label',
        'value',
        'score',
        'order',
    ];

    public function screeningQuestion(): BelongsTo
    {
        return $this->belongsTo(ScreeningQuestion::class);
    }
}
