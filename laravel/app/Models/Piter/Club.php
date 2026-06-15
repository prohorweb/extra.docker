<?php

namespace App\Models\Piter;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $connection = 'piter';
    protected $table = 'club';
    public $timestamps = false;
}
