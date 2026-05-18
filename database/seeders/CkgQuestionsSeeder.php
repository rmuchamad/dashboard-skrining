<?php

namespace Database\Seeders;

use App\Models\ScreeningOption;
use App\Models\ScreeningQuestion;
use App\Support\CkgScreeningCatalog;
use Illuminate\Database\Seeder;

class CkgQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (CkgScreeningCatalog::questions() as $def) {
            $question = ScreeningQuestion::updateOrCreate(
                ['code' => $def['code']],
                [
                    'text' => $def['text'],
                    'category' => $def['category'],
                    'dimension' => $def['dimension'] ?? null,
                    'type' => $def['type'],
                    'order' => $def['order'],
                ]
            );

            foreach ($def['options'] as $opt) {
                ScreeningOption::updateOrCreate(
                    [
                        'screening_question_id' => $question->id,
                        'value' => $opt['value'],
                    ],
                    [
                        'label' => $opt['label'],
                        'score' => $opt['score'] ?? 0,
                        'order' => $opt['order'] ?? 0,
                    ]
                );
            }
        }
    }
}
