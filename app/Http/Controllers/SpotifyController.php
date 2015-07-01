<?php namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: pattem92
 * Date: 19/06/2015
 * Time: 11:09
 */
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Request;

class SpotifyController extends Controller {

    protected $oauth_token;
    protected $csrf_token;
    protected $url = "https://random.spotilocal.com:4371";
    protected $token_url = "https://open.spotify.com/token";
    protected $guzzle;

    /*SpotifyRemote.Error = {
        TRACK_UNAVAILABLE: "4303",
        USER_NOT_LOGGED_IN: "4110",
        message: {
            4001: "Unknown method",
            4002: "Error parsing request",
            4003: "Unknown service",
            4004: "Service not responding",
            4102: "Invalid OAuthToken",
            4103: "Expired OAuth token",
            4104: "OAuth token not verified",
            4105: "Token verification denied, too many requests",
            4106: "Token verification timeout",
            4107: "Invalid Csrf token",
            4108: "OAuth token is invalid for current user",
            4109: "Invalid Csrf path",
            4110: "No user logged in",
            4111: "Invalid scope",
            4112: "Csrf challenge failed",
            4201: "Upgrade to premium",
            4202: "Upgrade to premium or wait",
            4203: "Billing failed",
            4204: "Technical error",
            4205: "Commercial is playing",
            4301: "Content is unavailable but can be purchased",
            4302: "Premium only content",
            4303: "Content unavailable",
            "default": "Unexpected error. Please try again later."
        }
    };*/

    public function __construct(){

        $this->guzzle = New \GuzzleHttp\Client(['headers' => ["Origin" => 'https://open.spotify.com']]);

    }
    public function status() {
        if(!Request::input('oauth_token') && !Request::input('csrf_token')) {
            return ['fail'];
        }
        $this->oauth_token = Request::input('oauth_token');
        $this->csrf_token = Request::input('csrf_token');

        return json_decode($response = $this->guzzle->get($this->url . "/remote/status.json", [
            'query' => [
                'ref' => '',
                'cors' => '',
                'oauth' => $this->oauth_token,
                'csrf' => $this->csrf_token
            ]
        ])->getBody(), true);


    }

    public function open(){
        if(!Request::input('oauth_token') && !Request::input('csrf_token')) {
            return ['fail'];
        }
        $this->oauth_token = Request::input('oauth_token');
        $this->csrf_token = Request::input('csrf_token');

        return json_decode($response = $this->guzzle->get($this->url ."/remote/open.json", [
            'query' => [
                'ref' => '',
                'cors' => '',
                'oauth' => $this->oauth_token,
                'csrf' => $this->csrf_token
            ]
        ])->getBody(), true);
    }

    public function auth(){

        $oauth_token = json_decode($this->guzzle->get($this->token_url)->getBody(), true)['t'];

        $csrf_token = json_decode($this->guzzle->get($this->url . "/simplecsrf/token.json", [
            //'debug' => true,
            'query' => [
                'ref' => '',
                'cors' => '',
            ]
        ])->getBody(), true)['token'];

        return [
            'oauth' => $oauth_token,
            'csrf' => $csrf_token
        ];


    }

    public function play($id){
        if(!Request::input('oauth_token') && !Request::input('csrf_token')) {
            return ['fail'];
        }
        $this->oauth_token = Request::input('oauth_token');
        $this->csrf_token = Request::input('csrf_token');

        if(!$id == "0") {
            //$id = "spotify:track:".$id;
            return json_decode($response = $this->guzzle->get($this->url . "/remote/play.json", [
                //'debug' => true,
                'query' => [
                    'ref' => '',
                    'cors' => '',
                    'uri' => $id,
                    'context' => $id,
                    'oauth' => $this->oauth_token,
                    'csrf' => $this->csrf_token
                ]
            ])->getBody(), true);
        }
        return json_decode($response = $this->guzzle->get($this->url ."/remote/pause.json", [
            //'debug' => true,
            'query' => [
                'ref' => '',
                'cors' => '',
                'pause' => False,
                'oauth' => $this->oauth_token,
                'csrf' => $this->csrf_token
            ]
        ])->getBody(), true);

    }

    public function pause(){
        if(!Request::input('oauth_token') && !Request::input('csrf_token')) {
            return ['fail'];
        }
        $this->oauth_token = Request::input('oauth_token');
        $this->csrf_token = Request::input('csrf_token');


        return json_decode($response = $this->guzzle->get($this->url ."/remote/pause.json", [
            'query' => [
                'ref' => '',
                'cors' => '',
                'pause' => 'true',
                'oauth' => $this->oauth_token,
                'csrf' => $this->csrf_token
            ]
        ])->getBody(), true);
    }

}