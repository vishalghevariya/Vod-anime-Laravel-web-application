<?php

namespace App\Console\Commands;

use App\Models\Genre;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class ImportJikanGenres extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jikan:import-genres';

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
            //create api for fetch genre
            $genreUrl = config('app.jikan_base_url') . 'genres/anime';
            $apiUrl = env('JIKAN_IMPORT_GENRES_API', $genreUrl);

            // Use cURL to fetch data
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json',
            ]);

            // Allow cURL to work with non-HTTPS localhost URLs
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200 || !$response) {
                $this->error('Failed to fetch data from Jikan API. HTTP Code: ' . $httpCode);
                return Command::FAILURE;
            }

            $data = json_decode($response, true);

            if (!isset($data['data'])) {
                $this->error('Invalid response structure from API.');
                return Command::FAILURE;
            }

            $genres = $data['data'];
            $insertData = [];
            $now = now();

            foreach ($genres as $genre) {
                // Prepare data for bulk insertion
                $insertData[] = [
                    'mal_id' => $genre['mal_id'],
                    'name' => $genre['name'],
                    'url' => $genre['url'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Bulk insert data into genres  table
            Genre::upsert($insertData, ['mal_id'], ['name', 'url', 'updated_at']);

            $this->info('Genres imported successfully!');
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->info($e->getMessage());
        }
    }
}
