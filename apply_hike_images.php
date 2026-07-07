<?php
/**
 * Script untuk memasukkan gambar ke setiap node di horror_hike_nodes.json
 * berdasarkan kecocokan alur cerita dengan nama file gambar.
 * 
 * Setiap node ALUR/ENDING mendapatkan gambar unik dari folder public/Horror/Pendakian/
 */

$filePath = __DIR__ . '/storage/app/horror_hike_nodes.json';
if (!file_exists($filePath)) {
    die("ERROR: File tidak ditemukan: $filePath\n");
}

$data = json_decode(file_get_contents($filePath), true);
if (!$data) {
    die("ERROR: Gagal decode JSON. Pastikan format file valid.\n");
}

// Mapping node_id => path gambar (relatif ke folder public/)
// SETIAP NODE MENDAPAT GAMBAR UNIK SESUAI ALURNYA
$mapping = [
    // ======================================================
    // OPENING — Narasi pembuka
    // ======================================================
    'NARASI_AWAL'   => 'Horror/Pendakian/landing page pendakian horror.png',

    // ======================================================
    // CABANG 1: Ikuti bisikan
    // ======================================================
    'ALUR_1'        => 'Horror/Pendakian/alur 1 ikuti bisikan.png',
    // Makan persediaan misterius
    'ALUR_1_1'      => 'Horror/Pendakian/alur 1.1 mati memakan persediaan.png',
    // Lanjut perjalanan → selamat tapi luka parah
    'ENDING_1'      => 'Horror/Pendakian/alur 1.2 meninggal akibat kelelahan.png',
    // Menunggu bantuan → mati infeksi
    'ENDING_2'      => 'Horror/Pendakian/alur 1.1 mati memakan persediaan.png',

    // Tidak pakai persediaan → temukan gua
    'ALUR_1_2'      => 'Horror/Pendakian/alur 1.2 murid petapa.png',
    // Masuk gua → jadi murid pertapa
    'ENDING_3'      => 'Horror/Pendakian/alur 1.2 murid petapa.png',
    // Lewati gua → mati kelelahan
    'ENDING_4'      => 'Horror/Pendakian/alur 1.2 meninggal akibat kelelahan.png',

    // ======================================================
    // CABANG 2: Abaikan bisikan → temukan peti
    // ======================================================
    'ALUR_2'        => 'Horror/Pendakian/alur 2 kotak peti.png',

    // Abaikan peti → percabangan jalan
    'ALUR_2_1'      => 'Horror/Pendakian/alur 2.1 tidak membuka peti dan bertemu percabangan jalan.png',
    // Jalan 1 → masuk pasar ghoib → jadi Badarawuhi
    'ENDING_5'      => 'Horror/Pendakian/alur 2.1 ending badarawuhi.png',
    // Jalan 2 → masuk istana ghoib → jadi Permaisuri
    'ENDING_6'      => 'Horror/Pendakian/alur 2.1 ending permaisuri.png',

    // Buka peti → temukan keris
    'ALUR_2_2'      => 'Horror/Pendakian/alur 2.2 membuka kotak peti.png',

    // Ambil keris → dihadang hantu
    'ALUR_2_2_1'    => 'Horror/Pendakian/alur 2.2 menemukan keris di peti.png',
    // Lawan hantu → menang → selamat
    'ENDING_7'      => 'Horror/Pendakian/alur 2.2.1.2.1 melawan hantu dan menang.png',
    // Kabur → selamat tapi dihantui
    'ENDING_8'      => 'Horror/Pendakian/alur 2.2.1.2.2 kabur tidak melawan hantu.png',

    // Tinggalkan keris → penasaran
    'ALUR_2_2_2'    => 'Horror/Pendakian/alur 2.2.1 kebingungan mengambil keris atau tidak.png',

    // Tidak kembali ambil keris → pilih jalan
    'ALUR_2_2_2_1'  => 'Horror/Pendakian/alur 2.1 memilih jalan 1 masuk pasar ghoib.png',
    // Jalan 1 → masuk pasar ghoib → jadi Badarawuhi
    'ENDING_9'      => 'Horror/Pendakian/alur 2.1 ending badarawuhi.png',
    // Jalan 2 → masuk istana ghoib → jadi Permaisuri
    'ENDING_10'     => 'Horror/Pendakian/alur 2.1 ending permaisuri.png',

    // Kembali ambil keris → dihadang hantu
    'ALUR_2_2_2_2'  => 'Horror/Pendakian/alur 2.2.1.2 dihadang oleh hantu dan memegang keris.png',
    // Lawan hantu → menang → selamat dan disegani
    'ENDING_11'     => 'Horror/Pendakian/alur 2.2.1.2.4 ending disegani.png',
    // Kabur → dihantui seumur hidup
    'ENDING_12'     => 'Horror/Pendakian/alur 2.2.1.2.3 ending ketakutan seumur hidup.png',
];

$totalChanged = 0;
$nodeReport = [];

foreach ($data as $nodeId => &$node) {
    if (!isset($mapping[$nodeId])) {
        $nodeReport[] = "[SKIP] $nodeId - tidak ada mapping gambar";
        continue;
    }

    $imagePath = $mapping[$nodeId];

    if (isset($node['variations'])) {
        $count = 0;
        foreach ($node['variations'] as &$variation) {
            $variation['image'] = $imagePath;
            $count++;
        }
        $totalChanged += $count;
        $nodeReport[] = "[OK]   $nodeId - $count variasi => $imagePath";
    }
}

// Tulis kembali ke file
file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

echo "=== LAPORAN PEMETAAN GAMBAR HORROR PENDAKIAN ===\n\n";
foreach ($nodeReport as $line) {
    echo "$line\n";
}
echo "\n==========================================\n";
echo "Total variasi yang diperbarui: $totalChanged\n";
echo "File berhasil disimpan: $filePath\n";
