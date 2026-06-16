<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Seo extends Model
{
    protected $table = 'seo';

    protected $fillable = [
        'meta_title', 'meta_description', 'og_image', 'schema_type', 'schema_json',
    ];

    protected $casts = ['schema_json' => 'array'];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
