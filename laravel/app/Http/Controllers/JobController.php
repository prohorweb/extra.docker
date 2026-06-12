<?php

namespace App\Http\Controllers;

class JobController extends Controller
{
    public function index()
    {
        return view('pages.jobs.index', [
            'seo' => null,
        ]);
    }
}
