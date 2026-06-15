<?php

namespace App\Http\Controllers;

use App\Models\Piter\ClubCard;
use App\Models\Piter\ClubCardParams;

class CardTypeController extends Controller
{
    public function index()
    {
        $models = ClubCard::active()->get();
        $params = ClubCardParams::first();
        return view('pages.card.type', compact('models', 'params'));
    }
}
