<?php

namespace App\Providers;

use App\Http\View\Composers\ClubComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        require_once app_path('helpers.php');

        View::composer('*', ClubComposer::class);

        if (!function_exists('vite_asset')) {
            function vite_asset(string $entry): string
            {
                static $manifest = null;
                if ($manifest === null) {
                    $manifestPath = public_path('build/manifest.json');
                    if (file_exists($manifestPath)) {
                        $manifest = json_decode(file_get_contents($manifestPath), true);
                    } else {
                        return '';
                    }
                }

                return asset('build/' . ($manifest[$entry]['file'] ?? ''));
            }
        }
    }
}
