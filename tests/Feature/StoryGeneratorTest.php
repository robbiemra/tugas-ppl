<?php

namespace Tests\Feature;

use App\Services\StoryGenerator;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StoryGeneratorTest extends TestCase
{
    /**
     * Test starting an initial story and asserting structure and properties.
     */
    public function test_generate_initial_story_returns_valid_format_and_image(): void
    {
        config(['services.openai.key' => 'test-api-key']);

        Http::fake([
            '*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'content' => 'Ini cerita pembuka gaib.',
                                'choices' => [
                                    ['choice_text' => 'Aksi Pertama'],
                                    ['choice_text' => 'Aksi Kedua']
                                ],
                                'is_ending' => false
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $generator = new StoryGenerator();
        $result = $generator->generateInitialStory('Robbie', 'Laki-laki', 'Horror', 'Pendakian');

        $this->assertEquals('Ini cerita pembuka gaib.', $result['content']);
        $this->assertCount(2, $result['choices']);
        $this->assertEquals('Aksi Pertama', $result['choices'][0]['choice_text']);
        $this->assertFalse($result['is_ending']);
        $this->assertEquals('Horror/Pendakian/intro awal.png', $result['image']);
    }

    /**
     * Test generating next node and verifying cleanup of markdown formatting in JSON response.
     */
    public function test_generate_next_node_returns_valid_format_and_cleans_markdown(): void
    {
        config(['services.openai.key' => 'test-api-key']);

        Http::fake([
            '*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => "```json\n{\n  \"content\": \"Cerita berlanjut...\",\n  \"choices\": [],\n  \"is_ending\": true\n}\n```"
                        ]
                    ]
                ]
            ], 200)
        ]);

        $generator = new StoryGenerator();
        $result = $generator->generateNextNode('Cerita sebelumnya', 'Lari');

        $this->assertEquals('Cerita berlanjut...', $result['content']);
        $this->assertEmpty($result['choices']);
        $this->assertTrue($result['is_ending']);
    }
}
