<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;

class ClubComposer
{
    public function compose(View $view): void
    {
        if (! $view->offsetExists('club')) {
            $view->with('club', current_club());
        }
    }
}
