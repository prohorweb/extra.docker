<?php

namespace App\Http\Controllers;

class CardTypeController extends Controller
{
    public function index()
    {
        return view('pages.card.type', [
            'seo' => null,
        ]);
    }
}
