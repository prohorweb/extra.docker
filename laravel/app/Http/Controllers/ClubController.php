<?php

namespace App\Http\Controllers;

class ClubController extends Controller
{
    public function index()
    {
        $club = current_club();
        if (! $club) {
            return redirect('/');
        }
        $settingPost = $club->settingPost();

        return view('pages.club.index', compact('club', 'settingPost'));
    }
}
