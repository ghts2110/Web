<?php

use Inertia\Inertia;
use App\Http\Controllers\PromptController;

Route::get('/', function () {
    return Inertia::render('Base');
});

Route::get('/prompts', [PromptController::class, 'index'])->name('prompts.index');
Route::post('/prompts', [PromptController::class, 'store'])->name('prompts.store');
