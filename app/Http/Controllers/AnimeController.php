<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use Exception;
use Illuminate\Http\Request;

class AnimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($animSlug = null)
    {
        try {
            // Fetching Data From Anime Table Using given slug with there images and genres....
            $animes =  Anime::select(
                'id',
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
            )->with('genres', 'images')->where('anime_slug', $animSlug)->first();

            if (!empty($animes)) {
                return response()->json(['data' => $animes, 'status' => true], 200);
            } else {
                return response()->json(['data' => "No Anime Available!!", 'status' => false], 400);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong!!', 'message' => $e->getMessage()], 500);
        }
    }
}
