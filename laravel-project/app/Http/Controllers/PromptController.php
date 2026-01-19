<?php

namespace App\Http\Controllers;

use App\Models\Prompt;
use App\Models\PromptVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PromptController extends Controller
{
    public function index(Request $request)
    {
        $prompts = Prompt::query()
            ->where('user_id', $request->user()->id)
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $prompt = Prompt::create([
            'name' => $data['name'],
            'user_id' => $request->user()->id,
        ]);

        PromptVersion::create([
            'prompt_id' => $prompt->id,
            'name' => $prompt->name . '.v0',
            'content' => '', 
        ]);

        return redirect()->route('base');
    }

    
    public function update(Request $request, Prompt $prompt)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $prompt->update($data);

        return redirect()->back();
    }

    
    public function destroy(Prompt $prompt)
    {
        $prompt->versions()->delete(); 
        $prompt->delete();

        return redirect()->back();
    }

    
}
