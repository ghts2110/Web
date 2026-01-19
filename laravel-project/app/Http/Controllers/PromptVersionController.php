<?php

namespace App\Http\Controllers;

use App\Models\PromptVersion;
use Illuminate\Http\Request;

class PromptVersionController extends Controller
{
    public function store(Request $request, Prompt $prompt)
    {
        $data = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $nextVersion = $prompt->versions()->count();

        PromptVersion::create([
            'prompt_id' => $prompt->id,
            'name' => $prompt->name.'.v'.$nextVersion,
            'content' => $data['content'],
        ]);

        return redirect()->route('base');
    }

    public function update(Request $request, PromptVersion $promptsVersion)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $promptsVersion->update($data);

        return redirect()->back();
    }

    public function destroy(PromptVersion $promptsVersion)
    {
        $promptsVersion->delete();

        return redirect()->back();
    }
}
