<?php

namespace App\Http\Controllers;

use App\Services\PostService;

class ServiceController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index()
    {
        $club = current_club();
        $services = $this->postService->getServices($club);

        return view('pages.services.index', compact('services'));
    }

    public function show(string $alias)
    {
        $service = $this->postService->getServiceBySlug($alias);
        $seo = $service->seo;

        return view('pages.services.show', compact('service', 'seo'));
    }
}
