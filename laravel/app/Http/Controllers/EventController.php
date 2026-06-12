<?php

namespace App\Http\Controllers;

class EventController extends Controller
{
    public function index()
    {
        return view('pages.events.index', [
            'seo' => null,
        ]);
    }
}
