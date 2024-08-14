<?php

use App\Livewire\ApiSection;
use Illuminate\Support\Facades\Route;

use App\Livewire\LandingPage;   

Route::get('/', LandingPage::class )->name('home');
Route::get('/api', ApiSection::class )->name('api');

