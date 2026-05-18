<?php

$yn = static fn (string $code, string $text, int $order) => [
    'code' => $code,
    'category' => 'aktivitas_fisik',
    'dimension' => $code,
    'text' => $text,
    'type' => 'single_choice',
    'order' => $order,
    'options' => [
        ['label' => 'Ya', 'value' => 'ya', 'score' => 0, 'order' => 1],
        ['label' => 'Tidak', 'value' => 'tidak', 'score' => 0, 'order' => 2],
    ],
];

$num = static fn (string $code, string $text, int $order) => [
    'code' => $code,
    'category' => 'aktivitas_fisik',
    'dimension' => $code,
    'text' => $text,
    'type' => 'numeric',
    'order' => $order,
    'options' => [],
];

return [
    $yn(
        'act_home_medium',
        'Apakah Anda melakukan aktivitas fisik sedang pada kegiatan rumah tangga/domestik (menyapu, mencuci baju manual, memasak, mengasuh anak, atau mengangkat beban < 20 kg)?',
        10
    ),
    $num('act_home_days', 'Berapa hari dalam satu minggu Anda melakukan aktivitas rumah tangga/domestik tersebut?', 11),
    $num('act_home_minutes', 'Dalam satu hari berapa menit waktu yang digunakan untuk aktivitas rumah tangga/domestik tersebut?', 12),

    $yn(
        'act_work_medium',
        'Apakah Anda melakukan aktivitas fisik sedang pada tempat kerja (mengangkat beban, memberi makan ternak, berkebun, membersihkan kendaraan)?',
        20
    ),
    $num('act_work_days', 'Berapa hari dalam satu minggu Anda melakukan aktivitas sedang di tempat kerja tersebut?', 21),
    $num('act_work_minutes', 'Dalam satu hari berapa menit waktu yang digunakan untuk aktivitas sedang di tempat kerja tersebut?', 22),

    $yn(
        'act_travel_medium',
        'Apakah Anda melakukan aktivitas fisik sedang dalam perjalanan (berjalan kaki atau bersepeda ke ladang/sawah/pasar/tempat kerja)?',
        30
    ),
    $num('act_travel_days', 'Berapa hari dalam satu minggu Anda melakukan aktivitas sedang dalam perjalanan tersebut?', 31),
    $num('act_travel_minutes', 'Dalam satu hari berapa menit waktu yang digunakan untuk aktivitas perjalanan tersebut?', 32),

    $yn(
        'act_sport_medium',
        'Apakah Anda melakukan olahraga intensitas sedang (latihan beban < 20 kg, senam aerobic, yoga, bola, bersepeda santai, berenang santai)?',
        40
    ),
    $num('act_sport_medium_days', 'Berapa hari dalam satu minggu Anda melakukan olahraga intensitas sedang tersebut?', 41),
    $num('act_sport_medium_minutes', 'Dalam satu hari berapa menit waktu yang digunakan untuk olahraga intensitas sedang tersebut?', 42),

    $yn(
        'act_work_heavy',
        'Apakah Anda melakukan aktivitas fisik intensitas berat di tempat kerja (mengangkat/memikul beban ≥20 kg, mencangkul, menggali, memanen, memanjat pohon, menarik jaring, mendorong/mesin pemotong rumput/gerobak/perahu/kendaraan)?',
        50
    ),
    $num('act_work_heavy_days', 'Berapa hari dalam satu minggu Anda melakukan aktivitas fisik intensitas berat di tempat kerja tersebut?', 51),
    $num('act_work_heavy_minutes', 'Dalam satu hari berapa menit waktu yang digunakan untuk aktivitas fisik intensitas berat di tempat kerja tersebut?', 52),

    $yn(
        'act_sport_heavy',
        'Apakah Anda melakukan olahraga intensitas berat (bersepeda cepat >16 km/jam, jalan cepat >7 km/jam, lari, sepak bola, futsal, bulutangkis, tenis, basket, lompat tali)?',
        60
    ),
    $num('act_sport_heavy_days', 'Berapa hari dalam satu minggu Anda melakukan olahraga intensitas berat tersebut?', 61),
    $num('act_sport_heavy_minutes', 'Dalam satu hari berapa menit waktu yang digunakan untuk olahraga intensitas berat tersebut?', 62),
];
