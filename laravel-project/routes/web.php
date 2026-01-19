<?php

use Inertia\Inertia;
use App\Http\Controllers\PromptController;
use App\Http\Controllers\PromptVersionController;
use Laravel\Fortify\Features;

// Route::get('/', function () {
//     return Inertia::render('Base');
// });

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/base', [PromptController::class, 'index'])->name('base');
    Route::post('/prompts', [PromptController::class, 'store'])->name('prompts.store');
    Route::post('/prompt-versions/{prompt}', [PromptVersionController::class, 'store'])->name('promptVersions.store');
    Route::patch('/prompt/{prompt}', [PromptController::class, 'update'])->name('prompts.update');
    Route::patch('/prompt-versions/{promptsVersion}', [PromptVersionController::class, 'update'])->name('promptVersions.update');
    Route::delete('/prompts/{prompt}', [PromptController::class, 'destroy'])->name('prompts.destroy');
    Route::delete('/prompt-versions/{promptsVersion}', [PromptVersionController::class, 'destroy'])->name('promptVersions.destroy');
});