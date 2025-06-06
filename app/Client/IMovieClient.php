<?php

namespace App\Client;

interface IMovieClient
{
    public function sendNewMovieNotification($movieTitle): bool | string;
}
