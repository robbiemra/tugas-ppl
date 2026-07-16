<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\GameEngine;

class GameEngineTest extends TestCase
{
    /**
     * Test the full Adventure Island story path and verify the images.
     */
    public function test_adventure_island_story_path_images(): void
    {
        $component = Livewire::test(GameEngine::class)
            ->set('userName', 'Robbie')
            ->set('userAge', 20)
            ->set('gender', 'Laki-laki')
            ->call('saveBiodata')
            ->assertSet('step', 'select_genre')
            ->call('pickGenre', 'Adventure')
            ->assertSet('step', 'select_location')
            // Start story on Pulau Terpencil
            ->call('startStory', 'Pulau Terpencil')
            ->assertSet('step', 'gameplay')
            ->assertSet('currentNodeId', 'NARASI_AWAL');

        // Check initial image
        $this->assertEquals(
            asset('Adventure/Pulau/Alur opening pulau terpencil mempersiapkan pembekalan.png'),
            $component->get('aiImageUrl')
        );

        // Choice: Pantai
        $component->call('selectChoice', 'Pantai')
            ->assertSet('currentNodeId', 'ALUR_1');
        $this->assertEquals(
            asset('Adventure/Pulau/Alur opening pilihan pertama tetap di pantai.png'),
            $component->get('aiImageUrl')
        );

        // Choice: Menunggu
        $component->call('selectChoice', 'Menunggu')
            ->assertSet('currentNodeId', 'ALUR_1_1');
        $this->assertEquals(
            asset('Adventure/Pulau/Alur 1.1 malam tiba membuat api dan menjaga api.png'),
            $component->get('aiImageUrl')
        );

        // Choice: Jaga Api
        $component->call('selectChoice', 'Jaga Api')
            ->assertSet('currentNodeId', 'ALUR_1_1_1')
            ->assertSet('step', 'ending');
        $this->assertEquals(
            asset('Adventure/Pulau/Alur 1.1.1 Ending 1 diselamatkan kapal di pagi hari.png'),
            $component->get('aiImageUrl')
        );
    }
}
