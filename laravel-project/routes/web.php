<?php

use Inertia\Inertia;
use App\Http\Controllers\PromptController;

Route::get('/', function () {
    return Inertia::render('Base');
});

Route::get('/prompts', [PromptController::class, 'index'])->name('prompts.index');
Route::post('/prompts', [PromptController::class, 'storeP'])->name('prompts.store');
Route::post('/prompt-versions/{prompt}', [PromptController::class, 'storePV'])->name('promptVersions.store');
Route::patch('/prompt/{prompt}', [PromptController::class, 'updateP'])->name('prompts.update');
Route::patch('/prompt-versions/{promptsVersion}', [PromptController::class, 'updatePV'])->name('promptVersions.update');
Route::delete('/prompts/{prompt}', [PromptController::class, 'destroyP'])->name('prompts.destroy');
Route::delete('/prompt-versions/{promptsVersion}', [PromptController::class, 'destroyPV'])->name('promptVersions.destroy');