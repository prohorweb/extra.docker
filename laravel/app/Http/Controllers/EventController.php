<?php

namespace App\Http\Controllers;

use App\Services\PostService;

class EventController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index()
    {
        $club = current_club();
        $upcoming = $this->postService->getUpcomingEvents($club);
        $past = $this->postService->getPastEvents($club);

        return view('pages.events.index', compact('upcoming', 'past'));
    }
}
