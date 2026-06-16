<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Taxonomy;
use App\Services\PostService;

class HomeController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index()
    {
        $club = current_club();

        if (! $club) {
            $clubs = Taxonomy::club()->active()->ordered()->get();
            $host = request()->getHost();

            $itemClubs = $clubs->map(fn (Taxonomy $c) => [
                'name' => $c->title,
                'address' => $c->settingPost()?->address ?? '',
                'image' => $this->postImageUrl($c->settingPost()?->img, 'img/clubs/welcom-block-img-2.jpg'),
                'url' => 'http://'.$c->slug.'.'.$host,
            ])->all();

            $placemarks = $clubs->map(fn (Taxonomy $c) => [
                'coordinates' => $c->settingPost()?->coordinates ?? '',
                'hint' => $c->title,
                'icon' => asset('img/marker.png'),
                'url' => 'http://'.$c->slug.'.'.$host,
            ])->filter(fn (array $m) => filled($m['coordinates']))->values()->all();

            return view('pages.welcome', [
                'hero' => [
                    'video' => asset('video/bg_moution.mp4'),
                    'logo' => asset('img/logo.svg'),
                    'heading' => 'Сеть фитнес клубов на результат!',
                    'cta' => [
                        'text' => 'Выберите клуб',
                        'url' => '#clubs',
                        'url-mobile' => '#clubs-mobile',
                    ],
                ],
                'clubs' => $itemClubs,
                'placemarks' => $placemarks,
                'seo' => null,
                'showHeader' => false,
            ]);
        }

        $settingPost = $club->settingPost();
        $banners = $this->postService->getBanners($club);
        $shares = Post::share()->active()->ordered()->forClubOrGlobal($club)->take(6)->get();

        return view('pages.home', [
            'club' => $this->clubToViewArray($club, $settingPost),
            'settingPost' => $settingPost,
            'banners' => $banners->map(fn (Post $banner) => $this->bannerToViewArray($banner))->all(),
            'shares' => $shares->map(fn (Post $share) => $this->shareToViewArray($share))->all(),
            'metros' => [],
            'seo' => null,
        ]);
    }

    private function clubToViewArray(Taxonomy $club, ?Post $settingPost): array
    {
        return [
            'title' => $club->title,
            'tel' => $settingPost?->tel ?? '',
            'email' => $settingPost?->email ?? '',
            'address' => $settingPost?->address ?? '',
            'start_work' => $settingPost?->working_hours ?? '',
            'start_work_weekend' => $settingPost?->working_hours_weekend ?? '',
            'coordinates' => $settingPost?->coordinates ?? '',
        ];
    }

    private function bannerToViewArray(Post $banner): array
    {
        return [
            'title' => $banner->title,
            'title2' => $banner->subtitle,
            'intro' => $banner->intro,
            'url' => $banner->slug ? url('/card/shares/'.$banner->slug) : null,
            'img' => $this->postImageUrl($banner->img, null, 'banners'),
            'video' => $banner->banner_video,
        ];
    }

    private function shareToViewArray(Post $share): array
    {
        return [
            'title' => $share->title,
            'title2' => $share->subtitle,
            'intro' => $share->intro,
            'img' => $share->img,
            'alias' => $share->slug,
        ];
    }

    private function postImageUrl(?string $path, ?string $fallback = null, string $prefix = ''): string
    {
        if (filled($path)) {
            if (str_starts_with($path, 'http') || str_starts_with($path, '/')) {
                return $path;
            }

            if (str_starts_with($path, 'uploads/')) {
                return asset('storage/'.$path);
            }

            return $prefix ? '/uploads/'.$prefix.'/'.$path : asset('storage/'.$path);
        }

        return $fallback ? asset($fallback) : '';
    }
}
