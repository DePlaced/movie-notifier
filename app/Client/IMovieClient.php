<?php

namespace App\Client;

interface IMovieClient
{
    public function sendNewMovieNotification($message): bool | string;
}
