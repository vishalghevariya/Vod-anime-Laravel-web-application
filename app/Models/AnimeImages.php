<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimeImages extends Model
{
    protected $table = "anime_images";

    protected $fillable = [
        'anime_id',
        'type',
        'image_url',
        'small_image_url',
        'large_image_url',
        'created_at',
        'updated_at'
    ];

    public function anime()
    {
        return $this->belongsTo(Anime::class, 'anime_id', 'mal_id');
    }
}
