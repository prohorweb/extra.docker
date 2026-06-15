<?php

namespace App\Models\Piter;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $connection = 'piter';
    protected $table = 'services';

    public function scopeActive($q)
    {
        return $q->where('status', 10)->orderBy('position');
    }
}
