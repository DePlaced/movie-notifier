<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * User Route
 */
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/**
 * Movie Notification Route for slack
 */
Route::post('slack/notify/{secret}', function ($secret, Request $request) {
    
    if ($secret !== config('services.cron.secret')) {
        abort(401, 'Unauthorized');
    }

    $slackSigningSecret = config('services.slack.notifications.signing_secret');
    $timestamp = $request->header('X-Slack-Request-Timestamp');
    $slackSignature = $request->header('X-Slack-Signature');
    $body = $request->getContent();

    $sig_basestring = 'v0:' . $timestamp . ':' . $body;
    $my_signature = 'v0=' . hash_hmac('sha256', $sig_basestring, $slackSigningSecret);

    if (!hash_equals($my_signature, $slackSignature)) {
        abort(401, 'Unauthorized');
    }

    \Artisan::call('app:movie-command');

    return response()->json([
        "response_type" => "ephemeral",
        "text" => "Movie notification triggered! :popcorn:"
    ]);
});
    
/**
 * Movie Notification Route for cron
 */
Route::post('cron/scheduler/{secret}', function ($secret, Request $request) {

    if ($secret !== config('services.cron.secret')) {
        abort(401, 'Unauthorized');
    }

    if ($request->getUser() !== config('services.cron.username') || $request->getPassword() !== config('services.cron.password')) {
        abort(401, 'Unauthorized');
    }

    \Artisan::call('schedule:run', ['--no-interaction' => true]);

    return response('Scheduler triggered!', 200);
});