<?php

namespace App\Models\Piter;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    protected $connection = 'piter';
    protected $table = 'shares';

    public function scopeActive($q)
    {
        return $q->where('status', 10)->orderBy('position', 'desc');
    }
}
