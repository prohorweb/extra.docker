<?php

namespace App\Http\Controllers;

use App\Models\Piter\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::active()->upcoming()->get();
        $eventsPast = Event::active()->past()->paginate(10);
        return view('pages.events.index', compact('events', 'eventsPast'));
    }
}
