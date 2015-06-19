<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->welcome();
});

$app->get('play/{id}', 'SpotifyController@play');
//$app->get('games/{id}', 'App\Http\Controllers\GamesController@show');

$app->get('pause', 'SpotifyController@pause');
$app->get('unpause', 'SpotifyController@unpause');