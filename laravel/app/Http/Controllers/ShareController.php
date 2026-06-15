<?php

namespace App\Http\Controllers;

use App\Models\Piter\Share;

class ShareController extends Controller
{
    public function index()
    {
        $shares = Share::active()->paginate(12);
        return view('pages.shares.index', compact('shares'));
    }

    public function show(string $alias)
    {
        $share = Share::where('alias', $alias)->where('status', 10)->firstOrFail();
        $related = Share::active()->where('id', '!=', $share->id)->take(4)->get();
        return view('pages.shares.show', compact('share', 'related'));
    }
}
