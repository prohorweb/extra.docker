<?php

namespace App\Http\Controllers;

use App\Services\PostService;

class ShareController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index()
    {
        $club = current_club();
        $shares = $this->postService->getShares($club);

        return view('pages.shares.index', compact('shares'));
    }

    public function show(string $alias)
    {
        $share = $this->postService->getShareBySlug($alias);
        $seo = $share->seo;

        return view('pages.shares.show', compact('share', 'seo'));
    }
}
