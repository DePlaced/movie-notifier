<?php

namespace App\Console\Commands;

use App\Client\IMovieClient;
use App\Client\ITmdbClient;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MovieCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:movie-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications about new movies';


    public function __construct(private readonly IMovieClient $movieClient, private readonly ITmdbClient $tmdbClient)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $todayReleasedMovies = $this->tmdbClient->getTodayReleasedMovies();
        $message = $this->buildSlackMovieListMessage($todayReleasedMovies);

        $this->info("Sending to Slack:\n" . $message);
        $response = $this->movieClient->sendNewMovieNotification($message);
        $this->info("Response: " . $response);
    }

    private function buildSlackMovieListMessage(array $movies): string
    {
        if (empty($movies)) {
            return "No movies were released today.";
        }

        $message = "New Movies " . Carbon::now()->format('d-M-y') . " :popcorn:\n";

        $message .= collect($movies)->take(10)->map(function ($movie) {
            $title        = $movie['title'] ?? 'Untitled';
            $releaseDate  = $movie['release_date'] ?? 'Unknown';
            $link         = "https://www.themoviedb.org/movie/{$movie['id']}";
            return "•{$title}: <{$link}|Details>";
        })->implode("\n");

        $message .= "\nGet more details at: https://movie-notifier-service.onrender.com/";

        return $message;
    }
}
