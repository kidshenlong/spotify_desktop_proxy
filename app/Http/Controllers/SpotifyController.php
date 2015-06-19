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

    public function __construct(){

        $this->guzzle = New \GuzzleHttp\Client(['headers' => ["Origin" => 'https://open.spotify.com']]);

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

        //$id = "spotify:track:".$id;
        return $response = $this->guzzle->get($this->url . "/remote/play.json", [
            //'debug' => true,
            'query' => [
                'ref' => '',
                'cors' => '',
                'uri' => $id,
                'context' => $id,
                'oauth' => $this->oauth_token,
                'csrf' => $this->csrf_token
            ]
        ])->getBody();


    }

    public function pause(){
        if(!Request::input('oauth_token') && !Request::input('csrf_token')) {
            return ['fail'];
        }
        $this->oauth_token = Request::input('oauth_token');
        $this->csrf_token = Request::input('csrf_token');


        return $response = $this->guzzle->get($this->url ."/remote/pause.json", [
            'query' => [
                'ref' => '',
                'cors' => '',
                'pause' => 'true',
                'oauth' => $this->oauth_token,
                'csrf' => $this->csrf_token
            ]
        ])->getBody();
    }
    public function unpause(){
        if(!Request::input('oauth_token') && !Request::input('csrf_token')) {
            return ['fail'];
        }
        $this->oauth_token = Request::input('oauth_token');
        $this->csrf_token = Request::input('csrf_token');

        return $response = $this->guzzle->get($this->url ."/remote/pause.json", [
            //'debug' => true,
            'query' => [
                'ref' => '',
                'cors' => '',
                'pause' => False,
                'oauth' => $this->oauth_token,
                'csrf' => $this->csrf_token
            ]
        ])->getBody();
    }

}