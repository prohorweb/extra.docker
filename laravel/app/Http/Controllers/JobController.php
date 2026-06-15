<?php

namespace App\Http\Controllers;

use App\Models\Piter\Job;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::active()->get();
        return view('pages.jobs.index', compact('jobs'));
    }
}
