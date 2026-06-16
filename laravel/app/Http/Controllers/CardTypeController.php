<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\PostService;

class CardTypeController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index()
    {
        $cards = $this->postService->getCards();
        $pageTitle = Setting::get('cards_page_title', 'Membership Cards');
        $pageContent = Setting::get('cards_page_content');
        $models = $cards;
        $params = (object) ['text' => $pageContent];

        return view('pages.card.type', compact('cards', 'pageTitle', 'pageContent', 'models', 'params'));
    }
}
