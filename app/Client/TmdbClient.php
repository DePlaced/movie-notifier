<?php

namespace App\Client;

use App\Client\ITmdbClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class TmdbClient implements ITmdbClient
{
    protected string $apiKey;
    protected string $apiUrl = 'https://api.themoviedb.org/3';

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
    }

    public function getTodayReleasedMovies(): array
    {   
        $fromDate = Carbon::now()->startOfDay()->toDateString();

        $response = Http::withoutVerifying()->get("{$this->apiUrl}/discover/movie", [ // SET SSL IN PRODUCTION
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
