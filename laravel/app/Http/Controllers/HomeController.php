<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $host = $request->getHost();

        // extra.new — главная страница с выбором клуба
        if (in_array($host, ['extra.new', 'www.extra.new'])) {
            return view('extra.home');
        }

        // de-vision.new и субдомены (.extra.new) — страница выбранного клуба
        if (in_array($host, ['de-vision.new', 'www.de-vision.new']) || str_contains($host, '.extra.new')) {
            $club = explode('.', $host)[0]; 
            return view('subdomain.home', compact('club'));
        }

        return view('welcome');
    }
}
