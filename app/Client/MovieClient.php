<?php

namespace App\Client;

use App\Client\IMovieClient;

class MovieClient implements IMovieClient
{
    public function sendNewMovieNotification($movieTitle): bool | string
    {
        $payload = json_encode(['text' => "New movie released: " . ($movieTitle ?? "Unknown Title") . " :popcorn:"]);
        $webhookUrl = config('logging.channels.slack.url');
        $ch = curl_init($webhookUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
