<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimeGenres extends Model
{
    protected $table = "anime_genres";
    protected $fillable = [
        'anime_id',
        'genre_id',
        'created_at',
        'updated_at',
    ];
}
