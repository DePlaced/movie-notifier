<?php

namespace App\Console\Commands;

use App\Client\IMovieClient;
use App\Client\ITmdbClient;
use Illuminate\Console\Command;
use App\Client\MovieClient;

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

        $message = "*New movies released today:*\n";
        foreach ($movies as $movie) {
            $movieTitle = $movie['title'] ?? "Unknown Title";
            $releaseDate = $movie['release_date'] ?? '';
            $message .= "• {$movieTitle}" . ($releaseDate ? " ({$releaseDate})" : "") . "\n";
        }
        return $message;
    }
}
