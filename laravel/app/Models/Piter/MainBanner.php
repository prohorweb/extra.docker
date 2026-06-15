<?php

namespace App\Models\Piter;

use Illuminate\Database\Eloquent\Model;

class MainBanner extends Model
{
    protected $connection = 'piter';
    protected $table = 'main_banners';

    public function scopeActive($q)
    {
        return $q->where('status', 10)->orderBy('position');
    }
}
