<?php

namespace App\Http\Middleware;

use App\Models\Taxonomy;
use Closure;
use Illuminate\Http\Request;

class ResolveClub
{
    public function handle(Request $request, Closure $next): mixed
    {
        $host = $request->getHost();
        $parts = explode('.', $host);

        if (count($parts) >= 3) {
            $subdomain = $parts[0];
            $club = Taxonomy::where('type', 'club')
                ->where('slug', $subdomain)
                ->where('status', 10)
                ->first();
            $request->attributes->set('current_club', $club);
        } else {
            $request->attributes->set('current_club', null);
        }

        return $next($request);
    }
}
