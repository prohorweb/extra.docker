<?php

namespace App\Models\Piter;

use Illuminate\Database\Eloquent\Model;

class TrainerOption extends Model
{
    protected $connection = 'piter';
    protected $table = 'trainer_options_type';
    public $timestamps = false;

    public function scopeActive($q)
    {
        return $q->where('status', 10)->orderBy('position');
    }
}
