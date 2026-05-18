<?php

$yn = static fn (int $yesScore) => [
    ['label' => 'Ya', 'value' => 'ya', 'score' => $yesScore, 'order' => 1],
    ['label' => 'Tidak', 'value' => 'tidak', 'score' => 0, 'order' => 2],
];

return [
    [
        'code' => 'lung_smoke_last_year',
        'category' => 'kanker_paru',
        'dimension' => 'smoking_last_year',
        'text' => 'Apakah Anda merokok dalam setahun terakhir ini?',
        'type' => 'single_choice',
        'order' => 10,
        'options' => $yn(2),
    ],
    [
        'code' => 'lung_smoke_history_15y',
        'category' => 'kanker_paru',
        'dimension' => 'smoking_history',
        'text' => 'Apakah Anda pernah memiliki riwayat merokok dalam 15 tahun terakhir?',
        'type' => 'single_choice',
        'order' => 20,
        'options' => $yn(2),
    ],
    [
        'code' => 'lung_passive_smoke',
        'category' => 'kanker_paru',
        'dimension' => 'passive_smoke',
        'text' => 'Apakah Anda terpapar atau menghirup asap rokok dari orang lain di rumah, lingkungan atau tempat kerja dalam 1 bulan terakhir?',
        'type' => 'single_choice',
        'order' => 30,
        'options' => $yn(1),
    ],
    [
        'code' => 'lung_family_lung_cancer',
        'category' => 'kanker_paru',
        'dimension' => 'family_lung_cancer',
        'text' => 'Apakah memiliki riwayat kanker paru pada keluarga (ayah/ibu/saudara kandung)?',
        'type' => 'single_choice',
        'order' => 40,
        'options' => $yn(2),
    ],
    [
        'code' => 'lung_persistent_symptoms',
        'category' => 'kanker_paru',
        'dimension' => 'lung_symptoms',
        'text' => 'Apakah Anda sedang mengalami salah satu atau lebih gejala berikut dan telah diobati tetapi tidak sembuh-sembuh: batuk lama/batuk berdarah/sesak/nyeri dada/leher bengkak/benjolan leher?',
        'type' => 'single_choice',
        'order' => 50,
        'options' => $yn(2),
    ],
    [
        'code' => 'lung_tbc_ppok_history',
        'category' => 'kanker_paru',
        'dimension' => 'tbc_ppok',
        'text' => 'Apakah Anda pernah memiliki riwayat penyakit TBC atau PPOK?',
        'type' => 'single_choice',
        'order' => 60,
        'options' => $yn(2),
    ],
];
