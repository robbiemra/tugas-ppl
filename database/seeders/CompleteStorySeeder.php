<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoryNode;
use App\Models\StoryChoice;

class CompleteStorySeeder extends Seeder
{
    public function run()
    {
        // Pastikan nama lokasi ini SAMA PERSIS dengan value yang ada di dropdown/form biodata Anda
        $locMain = 'Rumah Sakit'; 

        // 1. NODE AWAL
        $start = StoryNode::create([
            'genre' => 'Horror', 
            'location' => $locMain, 
            'content' => 'Perjalanan dimulai. Anda berdiri di depan Rumah Sakit tua yang mencekam. Apakah Anda siap?', 
            'is_ending' => false
        ]);

        // --- JALUR KIRI: JURANG ---
        $n2 = StoryNode::create(['genre' => 'Horror', 'location' => $locMain, 'content' => 'Anda terperosok ke lubang lift yang rusak dan terjatuh ke area bawah tanah yang gelap (Jurang).', 'is_ending' => false]);
        $n3 = StoryNode::create(['genre' => 'Horror', 'location' => $locMain, 'content' => 'Anda terbangun dengan rasa sakit yang luar biasa. Kaki Anda sepertinya patah. Bertahan?', 'is_ending' => false]);
        $n4 = StoryNode::create(['genre' => 'Horror', 'location' => $locMain, 'content' => 'Anda mencoba menyeret tubuh mencari jalan keluar, namun lorong ini terasa tidak berujung.', 'is_ending' => false]);
        $e1 = StoryNode::create(['genre' => 'Horror', 'location' => $locMain, 'content' => 'Luka Anda terlalu parah dan infeksi mulai menyebar. Anda meninggal dalam kesunyian.', 'is_ending' => true]);

        // --- JALUR TENGAH: PIMON & PETI ---
        $n5 = StoryNode::create(['genre' => 'Horror', 'location' => $locMain, 'content' => 'Sesosok makhluk mengerikan, Pimon Bewir, muncul dari kegelapan menghalangi jalan Anda. Di sampingnya terdapat sebuah peti kayu tua.', 'is_ending' => false]);
        $n6 = StoryNode::create(['genre' => 'Horror', 'location' => $locMain, 'content' => 'Anda memberanikan diri membuka peti tersebut. Di dalamnya tersimpan perhiasan dan emas kuno yang sangat banyak.', 'is_ending' => false]);
        $e2 = StoryNode::create(['genre' => 'Horror', 'location' => $locMain, 'content' => 'Selamat! Anda berhasil menemukan jalan keluar rahasia sambil membawa harta tersebut. Anda kini kaya raya!', 'is_ending' => true]);

        // --- JALUR KANAN: MATA BATIN ---
        $n7 = StoryNode::create(['genre' => 'Horror', 'location' => $locMain, 'content' => 'Dalam ketakutan, mata batin Anda tiba-tiba terbuka. Anda kini bisa melihat jalur tersembunyi menuju sebuah gua di bawah pondasi bangunan.', 'is_ending' => false]);
        $n8 = StoryNode::create(['genre' => 'Horror', 'location' => 'Gua', 'content' => 'Di dalam gua tersebut, Anda bertemu dengan seorang pertapa sakti yang sudah menunggu kedatangan Anda.', 'is_ending' => false]);
        $e3 = StoryNode::create(['genre' => 'Horror', 'location' => 'Gua', 'content' => 'Anda memutuskan untuk tinggal dan dilatih ilmu gaib, menjadi penerus sang Pertapa.', 'is_ending' => true]);

        // --- MENGHUBUNGKAN PILIHAN ---
        
        // Pilihan dari Start[cite: 1]
        StoryChoice::create(['story_node_id' => $start->id, 'choice_text' => 'Lari ke arah lift (Jurang)', 'next_node_id' => $n2->id]);
        StoryChoice::create(['story_node_id' => $start->id, 'choice_text' => 'Masuk ke ruang operasi (Temui Pimon)', 'next_node_id' => $n5->id]);
        StoryChoice::create(['story_node_id' => $start->id, 'choice_text' => 'Konsentrasi dan Buka Mata Batin', 'next_node_id' => $n7->id]);

        // Relasi Jalur Jurang
        StoryChoice::create(['story_node_id' => $n2->id, 'choice_text' => 'Coba untuk merangkak bangkit', 'next_node_id' => $n3->id]);
        StoryChoice::create(['story_node_id' => $n3->id, 'choice_text' => 'Cari celah keluar', 'next_node_id' => $n4->id]);
        StoryChoice::create(['story_node_id' => $n4->id, 'choice_text' => 'Menyerah pada keadaan', 'next_node_id' => $e1->id]);

        // Relasi Jalur Pimon
        StoryChoice::create(['story_node_id' => $n5->id, 'choice_text' => 'Buka Peti Misterius', 'next_node_id' => $n6->id]);
        StoryChoice::create(['story_node_id' => $n6->id, 'choice_text' => 'Ambil Harta dan Lari Keluar', 'next_node_id' => $e2->id]);

        // Relasi Jalur Pertapa
        StoryChoice::create(['story_node_id' => $n7->id, 'choice_text' => 'Masuk ke dalam Gua', 'next_node_id' => $n8->id]);
        StoryChoice::create(['story_node_id' => $n8->id, 'choice_text' => 'Menjadi Murid Pertapa', 'next_node_id' => $e3->id]);
    }
}