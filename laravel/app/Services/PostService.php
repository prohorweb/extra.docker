<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Taxonomy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PostService
{
    public function getServices(?Taxonomy $club): Collection
    {
        $query = Post::service()->active()->ordered();
        if ($club) {
            $query->forClubOrGlobal($club);
        }

        return $query->get();
    }

    public function getServiceBySlug(string $slug): Post
    {
        return Post::service()->active()->where('slug', $slug)->firstOrFail();
    }

    public function getNews(?Taxonomy $club, int $perPage = 10): LengthAwarePaginator
    {
        $query = Post::news()->active()->ordered();
        if ($club) {
            $query->forClubOrGlobal($club);
        }

        return $query->paginate($perPage);
    }

    public function getNewsBySlug(string $slug): Post
    {
        return Post::news()->active()->where('slug', $slug)->firstOrFail();
    }

    public function getShares(?Taxonomy $club, int $perPage = 12): LengthAwarePaginator
    {
        $query = Post::share()->active()->ordered();
        if ($club) {
            $query->forClubOrGlobal($club);
        }

        return $query->paginate($perPage);
    }

    public function getShareBySlug(string $slug): Post
    {
        return Post::share()->active()->where('slug', $slug)->firstOrFail();
    }

    public function getTrainers(?Taxonomy $club, ?int $specializationId = null): Collection
    {
        $query = Post::trainer()->active()->ordered();
        if ($club) {
            $query->forClubOrGlobal($club);
        }
        if ($specializationId) {
            $query->whereHas('terms', fn ($q) => $q->where('taxonomies.id', $specializationId)->where('type', 'specialization'));
        }

        return $query->with('terms')->get();
    }

    public function getTrainerBySlug(string $slug): Post
    {
        return Post::trainer()->active()->where('slug', $slug)->with('terms')->firstOrFail();
    }

    public function getUpcomingEvents(?Taxonomy $club): Collection
    {
        $query = Post::event()->active()->upcoming()->ordered();
        if ($club) {
            $query->forClubOrGlobal($club);
        }

        return $query->get();
    }

    public function getPastEvents(?Taxonomy $club, int $perPage = 10): LengthAwarePaginator
    {
        $query = Post::event()->active()->past()->ordered();
        if ($club) {
            $query->forClubOrGlobal($club);
        }

        return $query->paginate($perPage);
    }

    public function getJobs(?Taxonomy $club): Collection
    {
        $query = Post::job()->active()->ordered();
        if ($club) {
            $query->forClubOrGlobal($club);
        }

        return $query->get();
    }

    public function getCards(): Collection
    {
        return Post::card()->active()->ordered()->get();
    }

    public function getBanners(Taxonomy $club): Collection
    {
        return Post::share()->active()->banner()->forClub($club)->ordered()->get();
    }
}
