<?php

use Inertia\Inertia;
use App\Http\Controllers\PromptController;
use Laravel\Fortify\Features;

// Route::get('/', function () {
//     return Inertia::render('Base');
// });

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/base', function () {
    return Inertia::render('Base');
})->middleware(['auth', 'verified'])->name('base');


Route::get('/base', [PromptController::class, 'index'])->middleware(['auth', 'verified'])->name('base');
Route::post('/prompts', [PromptController::class, 'storeP'])->name('prompts.store');
Route::post('/prompt-versions/{prompt}', [PromptController::class, 'storePV'])->name('promptVersions.store');
Route::patch('/prompt/{prompt}', [PromptController::class, 'updateP'])->name('prompts.update');
Route::patch('/prompt-versions/{promptsVersion}', [PromptController::class, 'updatePV'])->name('promptVersions.update');
Route::delete('/prompts/{prompt}', [PromptController::class, 'destroyP'])->name('prompts.destroy');
Route::delete('/prompt-versions/{promptsVersion}', [PromptController::class, 'destroyPV'])->name('promptVersions.destroy');

