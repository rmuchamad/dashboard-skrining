<?php

return [
    [
        'code' => 'tb_cough',
        'category' => 'tb_dewasa_lansia',
        'dimension' => 'cough',
        'text' => 'Apakah Anda pernah atau sedang mengalami batuk yang tidak sembuh-sembuh?',
        'type' => 'single_choice',
        'order' => 10,
        'options' => [
            ['label' => 'Ya, lebih dari 2 minggu', 'value' => 'gt2w', 'score' => 3, 'order' => 1],
            ['label' => 'Ya, kurang dari 2 minggu', 'value' => 'lt2w', 'score' => 1, 'order' => 2],
            ['label' => 'Tidak batuk', 'value' => 'tidak', 'score' => 0, 'order' => 3],
        ],
    ],
];
