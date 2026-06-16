<?php

namespace App\Http\Controllers;

use App\Models\Taxonomy;
use App\Services\PostService;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index(Request $request)
    {
        $club = current_club();
        $jobs = $this->postService->getJobs($club);
        $departments = Taxonomy::department()->active()->ordered()->get();

        return view('pages.jobs.index', compact('jobs', 'departments'));
    }
}
