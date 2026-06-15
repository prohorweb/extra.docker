<?php

namespace App\Models\Piter;

use Illuminate\Database\Eloquent\Model;

class ClubCard extends Model
{
    protected $connection = 'piter';
    protected $table = 'club_cards';

    public function scopeActive($q)
    {
        return $q->where('status', 10)->orderBy('position');
    }
}
