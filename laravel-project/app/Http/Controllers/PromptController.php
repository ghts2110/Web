<?php

namespace App\Http\Controllers;

use App\Models\Prompt;
use App\Models\PromptVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PromptController extends Controller
{
    public function index()
    {
        $prompts = Prompt::query()
            ->orderBy('id')
            ->get(['id','name']);

        $promptsVersion = PromptVersion::query()
            ->orderBy('id')
            ->get(['id', 'prompt_id', 'name', 'content']);

        return Inertia::render('Base', [
            'prompts' => $prompts,
            'promptsVersion' => $promptsVersion,
        ]);
    }

    public function storeP(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $prompt = Prompt::create([
            'name' => $data['name'],
        ]);

        PromptVersion::create([
            'prompt_id' => $prompt->id,
            'name' => $prompt->name . '.v0',
            'content' => '', 
        ]);

        return redirect()->route('prompts.index');
    }

    public function storePV(Request $request, Prompt $prompt)
    {
        $data = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $nextVersion = $prompt->versions()->count();

        PromptVersion::create([
            'prompt_id' => $prompt->id,
            'name' => $prompt->name . '.v' . $nextVersion,
            'content' => $data['content'], 
        ]);

        return redirect()->route('prompts.index');
    }

    public function updateP(Request $request, Prompt $prompt)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $prompt->update($data);

        return redirect()->back();
    }

    public function updatePV(Request $request, PromptVersion $promptsVersion)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $promptsVersion->update($data);

        return redirect()->back();
    }

    public function destroyP(Prompt $prompt)
    {
        $prompt->versions()->delete(); 
        $prompt->delete();

        return redirect()->back();
    }

    public function destroyPV(PromptVersion $promptsVersion)
    {
        $promptsVersion->delete();

        return redirect()->back();
    }
}
