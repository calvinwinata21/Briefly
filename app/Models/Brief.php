<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Brief extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_title',
        'raw_brief_text',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mediaDrafts(): HasMany
    {
        return $this->hasMany(MediaDraft::class);
    }

    public function latestDraft(): HasOne
    {
        return $this->hasOne(MediaDraft::class)->latestOfMany();
    }
}
