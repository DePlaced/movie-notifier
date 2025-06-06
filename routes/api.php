<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('cron/notify/{secret}', function ($secret, Request $request) {
    
    if ($secret !== config('services.cron.secret')) {
        abort(403, 'Unauthorized');
    }

    $slackSigningSecret = config('services.slack.notifications.signing_secret');
    $timestamp = $request->header('X-Slack-Request-Timestamp');
    $slackSignature = $request->header('X-Slack-Signature');
    $body = $request->getContent();

    $sig_basestring = 'v0:' . $timestamp . ':' . $body;
    $my_signature = 'v0=' . hash_hmac('sha256', $sig_basestring, $slackSigningSecret);

    if (!hash_equals($my_signature, $slackSignature)) {
        Log::warning('Slack signature mismatch');
        abort(403, 'Invalid Slack signature');
    }

    \Artisan::call('app:movie-command');

    return response()->json([
        "response_type" => "ephemeral",
        "text" => "Movie notification triggered! :popcorn:"
    ]);
});

Route::get('cron/notify/{secret}', function ($secret, Request $request) {
    
    if ($secret !== config('services.cron.secret')) {
        abort(403, 'Unauthorized');
    }

    \Artisan::call('app:movie-command');

    return response()->json([
        "response_type" => "ephemeral",
        "text" => "Movie notification triggered! :popcorn:"
    ]);
});
