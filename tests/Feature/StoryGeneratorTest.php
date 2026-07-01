<?php

namespace Tests\Feature;

use App\Services\StoryGenerator;
use Tests\TestCase;

class StoryGeneratorTest extends TestCase
{
    /**
     * Test: generateInitialStory mengembalikan format dan struktur yang valid
     * menggunakan file JSON lokal (Horror Pendakian / horror_hike_nodes.json).
     */
    public function test_generate_initial_story_returns_valid_structure_for_horror_hike(): void
    {
        $generator = new StoryGenerator();
        $result = $generator->generateInitialStory(
            'Robbie',
            'Laki-laki',
            'Horror',
            'Pendakian',
            'Gunung Slamet',
            2  // variationIndex -> pilih Slamet (index ke-2 di format lama)
        );

        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('choices', $result);
        $this->assertArrayHasKey('is_ending', $result);
        $this->assertArrayHasKey('image', $result);
        $this->assertNotEmpty($result['content']);
        $this->assertFalse($result['is_ending']);
        $this->assertCount(2, $result['choices']);
        $this->assertArrayHasKey('choice_text', $result['choices'][0]);
        $this->assertArrayHasKey('next_node', $result['choices'][0]);
    }

    /**
     * Test: generateInitialStory mengembalikan format valid untuk Horror Rumah Sakit
     * menggunakan file horror_hospital_nodes.json (format baru: variations[]).
     */
    public function test_generate_initial_story_returns_valid_structure_for_horror_hospital(): void
    {
        $generator = new StoryGenerator();
        $result = $generator->generateInitialStory(
            'Dewi',
            'Perempuan',
            'Horror',
            'Rumah Sakit',
            'RS Parikesit',
            0
        );

        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('choices', $result);
        $this->assertArrayHasKey('is_ending', $result);
        $this->assertNotEmpty($result['content']);
        $this->assertFalse($result['is_ending']);
    }

    /**
     * Test: generateNextNode mengembalikan konten yang benar untuk node lanjutan
     * menggunakan file adventure_cave_nodes.json (format baru: variations[]).
     */
    public function test_generate_next_node_returns_valid_content_for_adventure_cave(): void
    {
        $generator = new StoryGenerator();
        $result = $generator->generateNextNode(
            'Adventure',
            'Gua Misterius',
            'Rian',
            'Gua Pindul',
            'ALUR_1',
            0
        );

        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('choices', $result);
        $this->assertArrayHasKey('is_ending', $result);
        $this->assertNotEmpty($result['content']);
        $this->assertFalse($result['is_ending']);
        $this->assertCount(2, $result['choices']);
    }

    /**
     * Test: generateNextNode mengembalikan ending (is_ending: true) untuk node ENDING
     * menggunakan file adventure_cave_nodes.json.
     */
    public function test_generate_next_node_returns_ending_correctly(): void
    {
        $generator = new StoryGenerator();
        $result = $generator->generateNextNode(
            'Adventure',
            'Gua Misterius',
            'Rian',
            'Gua Pindul',
            'ENDING_1',
            0
        );

        $this->assertArrayHasKey('content', $result);
        $this->assertTrue($result['is_ending']);
        $this->assertEmpty($result['choices']);
    }

    /**
     * Test: fallback darurat dikembalikan jika node tidak ditemukan dalam JSON.
     */
    public function test_generate_next_node_returns_error_fallback_for_missing_node(): void
    {
        $generator = new StoryGenerator();
        $result = $generator->generateNextNode(
            'Horror',
            'Rumah Sakit',
            'Kamu',
            'RS Misterius',
            'NODE_YANG_TIDAK_ADA',
            0
        );

        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('is_ending', $result);
        // Fallback mengembalikan is_ending = false dengan pilihan 'Coba Muat Ulang'
        $this->assertFalse($result['is_ending']);
        $this->assertNotEmpty($result['choices']);
    }

    /**
     * Test: generateInitialStory and generateNextNode returns the mapped image for Adventure Island.
     */
    public function test_generate_story_returns_mapped_image_for_adventure_island(): void
    {
        $generator = new StoryGenerator();
        
        // Test initial story (intro)
        $initial = $generator->generateInitialStory(
            'Robbie',
            'Laki-laki',
            'Adventure',
            'Pulau Terpencil',
            'Pulau Kumala',
            0
        );
        $this->assertArrayHasKey('image', $initial);
        $this->assertEquals('Adventure/Pulau/pulau terpencil ( awal ).png', $initial['image']);

        // Test next node
        $next = $generator->generateNextNode(
            'Adventure',
            'Pulau Terpencil',
            'Robbie',
            'Pulau Kumala',
            'ALUR_1',
            0
        );
        $this->assertArrayHasKey('image', $next);
        $this->assertEquals('Adventure/Pulau/Alur 1.png', $next['image']);
    }
}

