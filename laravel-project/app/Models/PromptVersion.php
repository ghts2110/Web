<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromptVersion extends Model
{
    protected $fillable = ['prompt_id', 'name', 'content'];

    public function prompt(): BelongsTo{
        return $this->belongsTo(Prompt::class);
    }
}
