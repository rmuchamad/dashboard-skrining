<?php

$freq = [
    ['label' => 'Tidak sama sekali', 'value' => '0', 'score' => 0, 'order' => 1],
    ['label' => 'Kurang dari 1 minggu', 'value' => '1', 'score' => 1, 'order' => 2],
    ['label' => 'Lebih dari 1 minggu', 'value' => '2', 'score' => 2, 'order' => 3],
    ['label' => 'Hampir setiap hari', 'value' => '3', 'score' => 3, 'order' => 4],
];

return [
    [
        'code' => 'phq_energy',
        'category' => 'kesehatan_jiwa',
        'dimension' => 'phq_energy',
        'text' => 'Dalam 2 minggu terakhir, seberapa sering Anda kurang/tidak bersemangat dalam melakukan kegiatan sehari-hari?',
        'type' => 'single_choice',
        'order' => 10,
        'options' => $freq,
    ],
    [
        'code' => 'phq_depressed',
        'category' => 'kesehatan_jiwa',
        'dimension' => 'phq_depressed',
        'text' => 'Dalam 2 minggu terakhir, seberapa sering Anda merasa murung, tertekan, atau putus asa?',
        'type' => 'single_choice',
        'order' => 20,
        'options' => $freq,
    ],
    [
        'code' => 'phq_anxious',
        'category' => 'kesehatan_jiwa',
        'dimension' => 'phq_anxious',
        'text' => 'Dalam 2 minggu terakhir, seberapa sering Anda merasa gugup, cemas, atau gelisah?',
        'type' => 'single_choice',
        'order' => 30,
        'options' => $freq,
    ],
    [
        'code' => 'phq_worry_control',
        'category' => 'kesehatan_jiwa',
        'dimension' => 'phq_worry',
        'text' => 'Dalam 2 minggu terakhir, seberapa sering Anda tidak mampu mengendalikan rasa khawatir?',
        'type' => 'single_choice',
        'order' => 40,
        'options' => $freq,
    ],
];
