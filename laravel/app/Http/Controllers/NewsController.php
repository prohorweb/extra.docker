<?php

namespace App\Http\Controllers;

use App\Models\Piter\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::active()->paginate(10);
        return view('pages.news.index', compact('news'));
    }

    public function show(string $alias)
    {
        $article = News::where('alias', $alias)->where('status', 10)->firstOrFail();
        $related = News::active()->where('id', '!=', $article->id)->take(3)->get();
        return view('pages.news.show', compact('article', 'related'));
    }
}
