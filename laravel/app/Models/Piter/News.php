<?php

namespace App\Models\Piter;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $connection = 'piter';
    protected $table = 'news';

    protected $casts = ['date' => 'date'];

    public function scopeActive($q)
    {
        return $q->where('status', 10)->orderBy('date', 'desc');
    }
}
