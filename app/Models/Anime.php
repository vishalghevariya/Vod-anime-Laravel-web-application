<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    protected $fillable = [
        'mal_id',
        'url',
        'approved',
        'title',
        'title_english',
        'title_japanese',
        'trailer_youtube_id',
        'trailer_url',
        'trailer_embed_url',
        'type',
        'source',
        'episodes',
        'status',
        'airing',
        'duration',
        'rating',
        'score',
        'scored_by',
        'rank',
        'popularity',
        'members',
        'favorites',
        'synopsis',
        'background',
        'season',
        'year',
        'created_at',
        'updated_at'
    ];

    public function images()
    {
        return $this->hasMany(AnimeImages::class, 'anime_id', 'mal_id');
    }

    public function genres()
    {
        return $this->belongsToMany(
            Genre::class,
            'anime_genres',
            'anime_id',
            'genre_id',
            'mal_id',
            'mal_id'
        );
    }
}
