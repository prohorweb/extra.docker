<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $host = $request->getHost();

        if (in_array($host, ['extra.new', 'www.extra.new'])) {
            return view('pages.welcome');
        }

        if (in_array($host, ['de-vision.new', 'www.de-vision.new']) || str_contains($host, '.extra.new')) {
            return view('pages.home');
        }

        return view('pages.welcome');
    }
}
