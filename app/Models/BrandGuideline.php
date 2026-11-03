<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandGuideline extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_name',
        'primary_color_hex',
        'secondary_color_hex',
        'document_path',
        'vector_metadata',
    ];

    protected function casts(): array
    {
        return [
            'vector_metadata' => 'array',
        ];
    }
}
