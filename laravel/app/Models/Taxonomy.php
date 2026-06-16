<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Taxonomy extends Model
{
    protected $fillable = ['type', 'title', 'slug', 'position', 'status', 'parent_id'];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_term');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Taxonomy::class, 'parent_id');
    }

    public function scopeClubs($query)
    {
        return $query->where('type', 'club');
    }

    public function scopeClub($query)
    {
        return $query->where('type', 'club');
    }

    public function scopeCategories($query)
    {
        return $query->where('type', 'category');
    }

    public function scopeSpecializations($query)
    {
        return $query->where('type', 'specialization');
    }

    public function scopeSpecialization($query)
    {
        return $query->where('type', 'specialization');
    }

    public function scopeDepartments($query)
    {
        return $query->where('type', 'department');
    }

    public function scopeDepartment($query)
    {
        return $query->where('type', 'department');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 10);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    public function settingPost()
    {
        return $this->posts()->where('type', 'setting')->first();
    }

    public function settingPosts(): BelongsToMany
    {
        return $this->posts()->where('type', 'setting');
    }

    public function bannerPosts(): BelongsToMany
    {
        return $this->posts()->where('type', 'share')->where('is_banner', true);
    }

    public function sharePosts(): BelongsToMany
    {
        return $this->posts()->where('type', 'share')->where('is_banner', false);
    }

    public function eventPosts(): BelongsToMany
    {
        return $this->posts()->where('type', 'event');
    }
}
