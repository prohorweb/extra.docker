<?php

namespace App\Http\Controllers;

use App\Services\PostService;

class NewsController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index()
    {
        $club = current_club();
        $news = $this->postService->getNews($club);

        return view('pages.news.index', compact('news'));
    }

    public function show(string $alias)
    {
        $post = $this->postService->getNewsBySlug($alias);
        $related = $this->postService->getNews(current_club(), 3);
        $seo = $post->seo;

        return view('pages.news.show', compact('post', 'related', 'seo'));
    }
}
