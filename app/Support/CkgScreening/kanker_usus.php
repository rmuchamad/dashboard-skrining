<?php

return [
    [
        'code' => 'colon_family_history',
        'category' => 'kanker_usus',
        'dimension' => 'colon_cancer_family',
        'text' => 'Apakah ada anggota keluarga Anda yang pernah dinyatakan menderita kanker kolorektal atau kanker usus?',
        'type' => 'single_choice',
        'order' => 10,
        'options' => [
            ['label' => 'Ya', 'value' => 'ya', 'score' => 2, 'order' => 1],
            ['label' => 'Tidak', 'value' => 'tidak', 'score' => 0, 'order' => 2],
        ],
    ],
    [
        'code' => 'colon_smoking',
        'category' => 'kanker_usus',
        'dimension' => 'smoking',
        'text' => 'Apakah Anda merokok?',
        'type' => 'single_choice',
        'order' => 20,
        'options' => [
            ['label' => 'Ya', 'value' => 'ya', 'score' => 2, 'order' => 1],
            ['label' => 'Tidak', 'value' => 'tidak', 'score' => 0, 'order' => 2],
        ],
    ],
];
