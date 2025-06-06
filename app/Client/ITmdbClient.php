<?php

namespace App\Client;

interface ITmdbClient
{
    public function getTodayReleasedMovies(): array;
}