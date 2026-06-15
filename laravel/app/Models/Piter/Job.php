<?php

namespace App\Models\Piter;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $connection = 'piter';
    protected $table = 'jobs';

    public function scopeActive($q)
    {
        return $q->where('status', 10)->orderBy('position');
    }
}
