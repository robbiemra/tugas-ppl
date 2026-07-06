<?php

$filePath = __DIR__ . '/storage/app/horror_hike_nodes.json';
if (!file_exists($filePath)) {
    die("File not found: $filePath\n");
}

$data = json_decode(file_get_contents($filePath), true);
if (!$data) {
    die("Failed to decode JSON. Make sure the file format is valid.\n");
}

$mapping = [
    'NARASI_AWAL' => 'Horror/Pendakian/landing page pendakian horror.png',
    'ALUR_1'      => 'Horror/Pendakian/alur 1 ikuti bisikan.png',
    'ALUR_1_1'    => 'Horror/Pendakian/alur 1.1 mati memakan persediaan.png',
    'ENDING_1'    => 'Horror/Pendakian/alur 1.2 meninggal akibat kelelahan.png',
    'ENDING_2'    => 'Horror/Pendakian/alur 1.2 murid petapa.png',
    'ALUR_1_2'    => 'Horror/Pendakian/alur 1.2 menemukan goa.png',
    'ENDING_3'    => 'Horror/Pendakian/alur 2.2.1.2 dihadang oleh hantu dan memegang keris.png',
    'ENDING_4'    => 'Horror/Pendakian/alur 2.2.1.2.3 ending ketakutan seumur hidup.png',
    'ALUR_2'      => 'Horror/Pendakian/alur 2 kotak peti.png',
    'ALUR_2_1'    => 'Horror/Pendakian/alur 2.1 tidak membuka peti dan bertemu percabangan jalan.png',
    'ENDING_5'    => 'Horror/Pendakian/alur 2.1 memilih jalan 1 masuk pasar ghoib.png',
    'ENDING_6'    => 'Horror/Pendakian/alur 2.1 memasuki istana ghoib.png',
    'ALUR_2_2'    => 'Horror/Pendakian/alur 2.2 membuka kotak peti.png',
    'ALUR_2_2_1'  => 'Horror/Pendakian/alur 2.2 menemukan keris di peti.png',
    'ENDING_7'    => 'Horror/Pendakian/alur 2.2.1.2.1 melawan hantu dan menang.png',
    'ENDING_8'    => 'Horror/Pendakian/alur 2.2.1.2.2 kabur tidak melawan hantu.png',
    'ALUR_2_2_2'  => 'Horror/Pendakian/alur 2.2.1 kebingungan mengambil keris atau tidak.png',
    'ALUR_2_2_2_1'=> 'Horror/Pendakian/alur 2.2.1.2 dihadang oleh hantu dan memegang keris.png',
    'ENDING_9'    => 'Horror/Pendakian/alur 2.2.1.2.4 ending disegani.png',
    'ENDING_10'   => 'Horror/Pendakian/alur 2.2.1.2.3 ending ketakutan seumur hidup.png',
    'ALUR_2_2_2_2'=> 'Horror/Pendakian/alur 2.2.1.2 dihadang oleh hantu dan memegang keris.png',
    'ENDING_11'   => 'Horror/Pendakian/alur 2.1 ending badarawuhi.png',
    'ENDING_12'   => 'Horror/Pendakian/alur 2.1 ending permaisuri.png',
];

$changed = 0;
foreach ($data as $nodeId => &$node) {
    if (isset($node['variations'])) {
        foreach ($node['variations'] as &$variation) {
            if (isset($mapping[$nodeId])) {
                $variation['image'] = $mapping[$nodeId];
                $changed++;
            }
        }
    }
}

file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo "Successfully updated $changed variations in horror_hike_nodes.json\n";
