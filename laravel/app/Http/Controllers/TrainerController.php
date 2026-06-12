<?php

namespace App\Http\Controllers;

class TrainerController extends Controller
{
    public function index()
    {
        return view('pages.trainers.index', [
            'seo' => null,
        ]);
    }
}
