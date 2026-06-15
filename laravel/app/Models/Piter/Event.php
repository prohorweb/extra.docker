<?php

namespace App\Models\Piter;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $connection = 'piter';
    protected $table = 'events';

    protected $casts = ['date' => 'date'];

    public function scopeActive($q)
    {
        return $q->where('status', 10);
    }

    public function scopeUpcoming($q)
    {
        return $q->where('date', '>=', now()->toDateString())->orderBy('date');
    }

    public function scopePast($q)
    {
        return $q->where('date', '<', now()->toDateString())->orderBy('date', 'desc');
    }
}
