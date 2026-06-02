<?php

namespace App\Livewire;

use Livewire\Component;

class LandingPage extends Component
{
    public function startAdventure()
    {
        // Fungsi ini akan mengarahkan user ke route /play (Game Engine)
        return redirect()->to('/play');
    }

    public function render()
{
    return view('livewire.landing-page')
           ->layout('livewire.components.layouts.app'); // Sesuaikan path ini
}
}