<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScreeningQuestion extends Model
{
    protected $fillable = [
        'code',
        'text',
        'category',
        'dimension',
        'type',
        'order',
    ];

    public function screeningOptions(): HasMany
    {
        return $this->hasMany(ScreeningOption::class)->orderBy('order');
    }

    public function screeningAnswers(): HasMany
    {
        return $this->hasMany(ScreeningAnswer::class);
    }
}
