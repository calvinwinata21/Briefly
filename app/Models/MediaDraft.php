<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaDraft extends Model
{
    use HasFactory;

    protected $fillable = [
        'brief_id',
        'reviewed_by',
        'ai_output_json',
        'approval_status',
        'designer_feedback',
    ];

    protected function casts(): array
    {
        return [
            'ai_output_json' => 'array',
        ];
    }

    public function brief(): BelongsTo
    {
        return $this->belongsTo(Brief::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
