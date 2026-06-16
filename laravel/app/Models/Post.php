<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Post extends Model
{
    protected $fillable = [
        'type', 'status', 'position', 'title', 'slug', 'intro', 'content', 'img',
        'subtitle', 'price', 'color', 'button_code', 'date', 'is_paid', 'is_open',
        'is_banner', 'banner_position', 'banner_video', 'banner_img_mobile',
        'tel', 'email', 'address', 'coordinates', 'working_hours', 'working_hours_weekend',
        'published_at',
    ];

    protected $casts = [
        'date' => 'date',
        'is_paid' => 'boolean',
        'is_open' => 'boolean',
        'is_banner' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function terms(): BelongsToMany
    {
        return $this->belongsToMany(Taxonomy::class, 'post_term');
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    public function scopeForClub($query, Taxonomy $club)
    {
        return $query->whereHas('terms', fn ($q) => $q->where('taxonomies.id', $club->id));
    }

    public function scopeForClubOrGlobal($query, Taxonomy $club)
    {
        return $query->where(function ($q) use ($club) {
            $q->whereHas('terms', fn ($q2) => $q2->where('taxonomies.id', $club->id))
                ->orWhereDoesntHave('terms', fn ($q2) => $q2->where('taxonomies.type', 'club'));
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 10);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position')->orderByDesc('date');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString())->orderBy('date');
    }

    public function scopePast($query)
    {
        return $query->where('date', '<', now()->toDateString())->orderByDesc('date');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeNews($query)
    {
        return $query->where('type', 'news');
    }

    public function scopeService($query)
    {
        return $query->where('type', 'service');
    }

    public function scopeShare($query)
    {
        return $query->where('type', 'share');
    }

    public function scopeEvent($query)
    {
        return $query->where('type', 'event');
    }

    public function scopeJob($query)
    {
        return $query->where('type', 'job');
    }

    public function scopeTrainer($query)
    {
        return $query->where('type', 'trainer');
    }

    public function scopeCard($query)
    {
        return $query->where('type', 'card');
    }

    public function scopeSetting($query)
    {
        return $query->where('type', 'setting');
    }

    public function scopeBanner($query)
    {
        return $query->where('is_banner', true);
    }

    public function getAliasAttribute(): ?string
    {
        return $this->slug;
    }
}
