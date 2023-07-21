<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class Movie extends Model
{
    use HasFactory, hasSlug, FilterQueryString;

    protected $fillable = [
        'title',
        'slug',
        'synopsis',
        'director',
        'duration',
        'releaseDate',
    ];

    protected $filters = [
        'sort',
        'greater',
        'greater_or_equal',
        'less',
        'less_or_equal',
        'between',
        'not_between',
        'in',
        'not_in',
        'like',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->usingSeparator('_');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function genre()
    {
        return $this->belongsToMany(MovieGenre::class);
    }

    public function favoritedUsers()
    {
        return $this->belongsToMany(User::class, 'favorite_movies', 'movie_id', 'user_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followed_movies', 'movie_id', 'user_id');
    }
}
