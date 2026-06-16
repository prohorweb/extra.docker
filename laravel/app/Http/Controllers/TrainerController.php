<?php

namespace App\Http\Controllers;

use App\Models\Taxonomy;
use App\Services\PostService;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index(Request $request)
    {
        $club = current_club();
        $specializationId = $request->integer('specialization') ?: null;
        $trainers = $this->postService->getTrainers($club, $specializationId);
        $specializations = Taxonomy::specialization()->active()->ordered()->get();
        $trainerOptions = $specializations;

        return view('pages.trainers.index', compact('trainers', 'specializations', 'specializationId', 'trainerOptions'));
    }

    public function show(string $alias)
    {
        $trainer = $this->postService->getTrainerBySlug($alias);
        $seo = $trainer->seo;

        return view('pages.trainers.show', compact('trainer', 'seo'));
    }
}
