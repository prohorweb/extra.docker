<?php

use App\Models\Taxonomy;
use Illuminate\Http\Request;

if (! function_exists('current_club')) {
    function current_club(): ?Taxonomy
    {
        /** @var Request|null $request */
        $request = app()->bound(Request::class) ? app(Request::class) : null;

        if ($request?->attributes->has('current_club')) {
            return $request->attributes->get('current_club');
        }

        return null;
    }
}
