<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LandingPage;
use App\Livewire\GameEngine;


// Halaman awal adalah Landing Page
Route::get('/', LandingPage::class);

// Halaman permainan
Route::get('/play', GameEngine::class);

// Route::get('/', function () {
//     return view('landing-page');
// });
