<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoryNode;
use App\Models\StoryChoice;

class HorrorHospitalSeeder extends Seeder
{
    public function run()
    {
        // --- JALUR KIRI (JURANG & BUNKER) ---
        $n1 = StoryNode::create(['genre' => 'Horror', 'location' => 'Rumah Sakit', 'content' => 'Anda terjepit di celah jurang yang sempit dan gelap.', 'is_ending' => false]);
        $n2 = StoryNode::create(['genre' => 'Horror', 'location' => 'Rumah Sakit', 'content' => 'Anda sadar dengan luka parah di sekujur tubuh.', 'is_ending' => false]);
        $n3 = StoryNode::create(['genre' => 'Horror', 'location' => 'Rumah Sakit', 'content' => 'Anda mencoba mencari jalan keluar di tengah kegelapan.', 'is_ending' => false]);
        $n4 = StoryNode::create(['genre' => 'Horror', 'location' => 'Rumah Sakit', 'content' => 'Kelaparan dan kehausan mulai menyerang Anda.', 'is_ending' => false]);
        $n5 = StoryNode::create(['genre' => 'Horror', 'location' => 'Rumah Sakit', 'content' => 'Anda menemukan sebuah lokasi pendakian tua yang terbengkalai.', 'is_ending' => false]);
        
        // Ending Jalur Kiri
        $e1 = StoryNode::create(['genre' => 'Horror', 'location' => 'Rumah Sakit', 'content' => 'Anda mati kelaparan dan kehausan di tengah jalan.', 'is_ending' => true]);
        $n6 = StoryNode::create(['genre' => 'Horror', 'location' => 'Rumah Sakit', 'content' => 'Anda bertemu dengan seorang pertapa tua yang misterius.', 'is_ending' => false]);
        $e2 = StoryNode::create(['genre' => 'Horror', 'location' => 'Rumah Sakit', 'content' => 'Anda berhasil dilatih ilmu gaib dan menjadi Murid Pertapa.', 'is_ending' => true]);

        // --- JALUR TENGAH (PIMON & PETI) ---
        $n7 = StoryNode::create(['genre' => 'Horror', 'location' => 'Rumah Sakit', 'content' => 'Pimon bewir muncul dengan tatapan yang sangat menyeramkan.', 'is_ending' => false]);
        $n8 = StoryNode::create(['genre' => 'Horror', 'location' => 'Rumah Sakit', 'content' => 'Anda menemukan sebuah peti kayu tua yang terkunci.', 'is_ending' => false]);
        $n9 = StoryNode::create(['genre' => 'Horror', 'location' => 'Rumah Sakit', 'content' => 'Di dalam peti, Anda menemukan harta karun kuno yang berkilau.', 'is_ending' => false]);
        
        // --- RELASI (CHOICES) ---
        // Start -> Jurang atau Pimon
        StoryChoice::create(['story_node_id' => $n1->id, 'choice_text' => 'Ikuti jalur jurang', 'next_node_id' => $n2->id]);
        StoryChoice::create(['story_node_id' => $n1->id, 'choice_text' => 'Abaikan dan temui Pimon', 'next_node_id' => $n7->id]);

        // Jalur Jurang -> Sadar -> Cari Jalan
        StoryChoice::create(['story_node_id' => $n2->id, 'choice_text' => 'Berjalan perlahan', 'next_node_id' => $n3->id]);
        StoryChoice::create(['story_node_id' => $n3->id, 'choice_text' => 'Telusuri lorong', 'next_node_id' => $n4->id]);
        
        // Kelaparan -> Makan/Minum (Mati) atau Terus (Pendakian)
        StoryChoice::create(['story_node_id' => $n4->id, 'choice_text' => 'Makan jamur liar', 'next_node_id' => $e1->id]);
        StoryChoice::create(['story_node_id' => $n4->id, 'choice_text' => 'Terus mendaki', 'next_node_id' => $n5->id]);

        // Pendakian -> Temui Pertapa
        StoryChoice::create(['story_node_id' => $n5->id, 'choice_text' => 'Masuk ke gua pertapa', 'next_node_id' => $n6->id]);
        StoryChoice::create(['story_node_id' => $n6->id, 'choice_text' => 'Belajar ilmu gaib', 'next_node_id' => $e2->id]);

        // Jalur Pimon -> Peti
        StoryChoice::create(['story_node_id' => $n7->id, 'choice_text' => 'Buka peti misterius', 'next_node_id' => $n8->id]);
        StoryChoice::create(['story_node_id' => $n8->id, 'choice_text' => 'Ambil isinya', 'next_node_id' => $n9->id]);
    }
}