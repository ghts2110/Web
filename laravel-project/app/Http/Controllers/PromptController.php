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
            ->orderBy('name')
            ->get(['id','name']);

        return Inertia::render('Base', [
            'prompts' => $prompts,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $prompt = Prompt::create([
            'name' => $data['name'],
        ]);

        PromptVersion::create([
            'prompt_id' => $prompt->id,
            'name' => '',
            'content' => '', 
        ]);

        return redirect()->route('prompts.index');
    }
}
