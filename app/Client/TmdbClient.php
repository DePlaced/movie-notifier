<?php

namespace App\Client;

use App\Client\ITmdbClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class TmdbClient implements ITmdbClient
{
    protected string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.tmdb.base_url');
        $this->apiKey = config('services.tmdb.api_key');
    }

    public function getTodayReleasedMovies(): array
    {
        $fromDate = Carbon::now()->startOfDay()->toDateString();

        $response = Http::withOptions([
            'verify' => true, // Ensure SSL verification is enabled
        ])->get("{$this->apiUrl}/discover/movie", [
            'api_key' => $this->apiKey,
            'language' => 'en-US',
            'sort_by' => 'popularity.desc',
            'primary_release_date.gte' => $fromDate,
            'primary_release_date.lte' => $fromDate,
            'region' => 'DK',
            'page' => 1,
        ]);

        return $response->json('results', []);
    }
}
