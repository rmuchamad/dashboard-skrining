<?php

$yn = static fn (int $yesScore) => [
    ['label' => 'Ya', 'value' => 'ya', 'score' => $yesScore, 'order' => 1],
    ['label' => 'Tidak', 'value' => 'tidak', 'score' => 0, 'order' => 2],
];

return [
    [
        'code' => 'smk_last_year',
        'category' => 'perilaku_merokok',
        'dimension' => 'smoking_last_year',
        'text' => 'Apakah Anda merokok dalam setahun terakhir ini?',
        'type' => 'single_choice',
        'order' => 10,
        'options' => $yn(2),
    ],
    [
        'code' => 'smk_type',
        'category' => 'perilaku_merokok',
        'dimension' => 'cigarette_type',
        'text' => 'Jika perokok, jenis rokok apa yang dikonsumsi?',
        'type' => 'single_choice',
        'order' => 20,
        'options' => [
            ['label' => 'Rokok konvensional (rokok putih, filter, kretek, tingwe, dll)', 'value' => 'konvensional', 'score' => 0, 'order' => 1],
            ['label' => 'Rokok elektronik (vape, IQOS, dll)', 'value' => 'vape', 'score' => 0, 'order' => 2],
            ['label' => 'Keduanya', 'value' => 'keduanya', 'score' => 0, 'order' => 3],
        ],
    ],
    [
        'code' => 'smk_years_current',
        'category' => 'perilaku_merokok',
        'dimension' => 'smoking_years',
        'text' => 'Sudah berapa tahun Anda merokok?',
        'type' => 'numeric',
        'order' => 30,
        'options' => [],
    ],
    [
        'code' => 'smk_sticks_per_day',
        'category' => 'perilaku_merokok',
        'dimension' => 'sticks_per_day',
        'text' => 'Biasanya, berapa batang rokok yang Anda hisap dalam sehari?',
        'type' => 'numeric',
        'order' => 40,
        'options' => [],
    ],
    [
        'code' => 'smk_years_before',
        'category' => 'perilaku_merokok',
        'dimension' => 'smoking_years_before',
        'text' => 'Berapa lama (tahun) Anda merokok sebelumnya?',
        'type' => 'numeric',
        'order' => 50,
        'options' => [],
    ],
    [
        'code' => 'smk_quit_when',
        'category' => 'perilaku_merokok',
        'dimension' => 'quit_smoking',
        'text' => 'Kapan Anda berhenti merokok?',
        'type' => 'single_choice',
        'order' => 60,
        'options' => [
            ['label' => '1 s.d <10 tahun lalu', 'value' => 'lt10', 'score' => 0, 'order' => 1],
            ['label' => '10 s.d <15 tahun lalu', 'value' => '10_15', 'score' => 0, 'order' => 2],
            ['label' => '15 tahun lalu atau lebih', 'value' => 'gte15', 'score' => 0, 'order' => 3],
        ],
    ],
    [
        'code' => 'smk_passive_last_month',
        'category' => 'perilaku_merokok',
        'dimension' => 'passive_smoke',
        'text' => 'Apakah Anda terpapar asap rokok atau menghirup asap rokok dari orang lain dalam sebulan terakhir?',
        'type' => 'single_choice',
        'order' => 70,
        'options' => $yn(1),
    ],
];
