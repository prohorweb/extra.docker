<?php

namespace App\Http\Controllers;

use App\Models\Piter\Trainer;
use App\Models\Piter\TrainerOption;

class TrainerController extends Controller
{
    public function index()
    {
        $trainerOptions = TrainerOption::active()->get();
        $trainers = Trainer::active()->get();
        return view('pages.trainers.index', compact('trainers', 'trainerOptions'));
    }

    public function show(string $alias)
    {
        $trainer = Trainer::where('alias', $alias)->where('status', 10)->firstOrFail();
        $others = Trainer::active()->where('id', '!=', $trainer->id)->take(5)->get();
        return view('pages.trainers.show', compact('trainer', 'others'));
    }
}
