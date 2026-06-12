<?php

namespace App\Http\Controllers;

class ShareController extends Controller
{
    public function index()
    {
        return view('pages.shares.index', [
            'seo' => null,
        ]);
    }
}
