<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $primaryKey = 'mal_id';

    protected $fillable = [
        'mal_id',
        'name',
        'url',
        'created_at',
        'updated_at'
    ];

    public function anime()
    {
        return $this->belongsToMany(Anime::class, 'anime_genres',  'genre_id', 'anime_id');
    }
}
