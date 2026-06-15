<?php

namespace App\Models\Piter;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $connection = 'piter';
    protected $table = 'trainers';

    public function scopeActive($q)
    {
        return $q->where('status', 10)->orderBy('position');
    }
}
