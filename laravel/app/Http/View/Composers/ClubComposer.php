<?php

namespace App\Http\View\Composers;

use App\Models\Piter\Club;
use Illuminate\View\View;

class ClubComposer
{
    private static $club = null;

    public function compose(View $view): void
    {
        if (self::$club === null) {
            self::$club = Club::first();
        }
        $view->with('club', self::$club);
    }
}
