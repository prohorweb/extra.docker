<?php

namespace App\Http\Controllers;

use App\Models\Piter\MainBanner;

class ClubController extends Controller
{
    public function index()
    {
        $banners = MainBanner::active()->get();
        return view('pages.club.index', compact('banners'));
    }
}
