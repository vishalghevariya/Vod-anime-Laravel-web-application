<?php

namespace App\Console\Commands;

use App\Models\Anime;
use App\Models\AnimeGenres;
use App\Models\AnimeImages;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Container\Attributes\DB;

class ImportJikanAnimes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jikan:import-animes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Fetching genres from Jikan API...');
            $animeUrl = config('app.jikan_base_url') . 'top/anime';
            $page = 1;
            AnimeImages::truncate();
            AnimeGenres::truncate();
            do {
                //create api for fetch animes
                $apiUrl = env('JIKAN_IMPORT_TOP_ANIMES_API', $animeUrl) . "?page=" . ($page ?? 1);
                $this->info($apiUrl);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Accept: application/json',
                ]);

                // Allow cURL to work with non-HTTPS localhost URLs
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

                //fetching data using api
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode !== 200 || !$response) {
                    $this->error('Failed to fetch data from Jikan API. HTTP Code: ' . $httpCode);
                    return Command::FAILURE;
                }
                $data = json_decode($response, true);
                if (!isset($data['data'])) {
                    $this->info('Invalid response structure from API.');
                }

                $animes = $data['data'];

                $animeInsertData = [];
                $imagesInsertData = [];
                $genreRelations = [];

                foreach ($animes as $animeData) {
                    // Prepare data for bulk insertion
                    $animeInsertData[] = [
                        'mal_id' => $animeData['mal_id'],
                        'url' => $animeData['url'],
                        'approved' => $animeData['approved'],
                        'title' => $animeData['title'],
                        'anime_slug' => generateSlug($animeData['title']),
                        'title_english' => $animeData['title_english'],
                        'title_japanese' => $animeData['title_japanese'],
                        'trailer_youtube_id' => $animeData['trailer']['youtube_id'] ?? null,
                        'trailer_url' => $animeData['trailer']['url'] ?? null,
                        'trailer_embed_url' => $animeData['trailer']['embed_url'] ?? null,
                        'type' => $animeData['type'],
                        'source' => $animeData['source'],
                        'episodes' => $animeData['episodes'],
                        'status' => $animeData['status'],
                        'airing' => $animeData['airing'],
                        'duration' => $animeData['duration'],
                        'rating' => $animeData['rating'],
                        'score' => $animeData['score'],
                        'scored_by' => $animeData['scored_by'],
                        'rank' => $animeData['rank'],
                        'popularity' => $animeData['popularity'],
                        'members' => $animeData['members'],
                        'favorites' => $animeData['favorites'],
                        'synopsis' => $animeData['synopsis'] ?? null,
                        'background' => $animeData['background'],
                        'season' => $animeData['season'],
                        'year' => $animeData['year'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // Prepare images for bulk insertion
                    foreach ($animeData['images'] as $type => $urls) {
                        $imagesInsertData[] = [
                            'anime_id' => $animeData['mal_id'],
                            'type' => $type,
                            'image_url' => $urls['image_url'] ?? null,
                            'small_image_url' => $urls['small_image_url'] ?? null,
                            'large_image_url' => $urls['large_image_url'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    // Prepare genre relations
                    foreach ($animeData['genres'] as $genreData) {
                        $genreRelations[] = [
                            'anime_id' => $animeData['mal_id'],
                            'genre_id' => $genreData['mal_id'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                // Bulk insert data into animes table
                Anime::upsert($animeInsertData, ['mal_id'], [
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
                    'updated_at'
                ]);

                // Bulk insert data into anime_images  table
                AnimeImages::insert($imagesInsertData);

                // Bulk insert data into anime_genres  table
                AnimeGenres::insert($genreRelations);
                $this->info(25 * $page . ' Anime imported successfully!');
                $page += 1;
            } while ($page <= 4);

            $this->info('Anime imported successfully!');
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->info($e->getMessage());
        }
    }
}
