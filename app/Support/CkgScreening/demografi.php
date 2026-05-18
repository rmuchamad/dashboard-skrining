<?php

return [
    [
        'code' => 'marital_status',
        'category' => 'demografi_dewasa',
        'dimension' => 'marital_status',
        'text' => 'Status Perkawinan',
        'type' => 'single_choice',
        'order' => 10,
        'options' => [
            ['label' => 'Belum Menikah', 'value' => 'belum_menikah', 'score' => 0, 'order' => 1],
            ['label' => 'Menikah', 'value' => 'menikah', 'score' => 0, 'order' => 2],
            ['label' => 'Cerai Mati', 'value' => 'cerai_mati', 'score' => 0, 'order' => 3],
            ['label' => 'Cerai Hidup', 'value' => 'cerai_hidup', 'score' => 0, 'order' => 4],
        ],
    ],
    [
        'code' => 'marriage_plan_1y',
        'category' => 'demografi_dewasa',
        'dimension' => 'marriage_plan',
        'text' => 'Apabila belum menikah / status cerai, apakah ada rencana menikah dalam kurun waktu 1 tahun ke depan?',
        'type' => 'single_choice',
        'order' => 20,
        'options' => [
            ['label' => 'Ya', 'value' => 'ya', 'score' => 0, 'order' => 1],
            ['label' => 'Tidak', 'value' => 'tidak', 'score' => 0, 'order' => 2],
        ],
    ],
    [
        'code' => 'disability_status',
        'category' => 'demografi_dewasa',
        'dimension' => 'disability',
        'text' => 'Apakah Anda penyandang disabilitas?',
        'type' => 'single_choice',
        'order' => 30,
        'options' => [
            ['label' => 'Non disabilitas', 'value' => 'non', 'score' => 0, 'order' => 1],
            ['label' => 'Penyandang disabilitas', 'value' => 'ya', 'score' => 0, 'order' => 2],
        ],
    ],
    [
        'code' => 'pregnancy_status',
        'category' => 'demografi_dewasa',
        'dimension' => 'pregnancy',
        'text' => 'Apakah Anda sedang hamil?',
        'type' => 'single_choice',
        'order' => 40,
        'options' => [
            ['label' => 'Ya', 'value' => 'ya', 'score' => 0, 'order' => 1],
            ['label' => 'Tidak', 'value' => 'tidak', 'score' => 0, 'order' => 2],
        ],
    ],
];
