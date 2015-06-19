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


        $this->oauth_token = Request::input('oauth_token');
        $this->csrf_token = Request::input('csrf_token');

        if(!Request::input('oauth_token') && !Request::input('csrf_token')) {


            $this->guzzle = New \GuzzleHttp\Client(['headers' => ["Origin" => 'https://open.spotify.com']]);
            $this->oauth_token = json_decode($this->guzzle->get($this->token_url)->getBody(), true)['t'];

            $this->csrf_token = json_decode($this->guzzle->get($this->url . "/simplecsrf/token.json", [
                //'debug' => true,
                'query' => [
                    'ref' => '',
                    'cors' => '',
                ]
            ])->getBody(), true)['token'];

        }



    }

    public function play($id){
        //dd($id);
        //dd($this->csrf_token);
        $id = "spotify:track:".$id;
        //$id = urlencode('spotify:track:' . $id);
        $thing = $this->guzzle->get($this->url . "/remote/play.json", [
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

        //dd($thing);

    }

    public function pause(){
        $response = $this->guzzle->get($this->url ."/remote/pause.json", [
            'query' => [
                'ref' => '',
                'cors' => '',
                'pause' => 'True',
                'oauth' => $this->oauth_token,
                'csrf' => $this->csrf_token
            ]
        ])->getBody();
    }
    public function unpause(){
        $response = $this->guzzle->get($this->url ."/remote/pause.json", [
            //'debug' => true,
            'query' => [
                'ref' => '',
                'cors' => '',
                'pause' => 'False',
                'oauth' => $this->oauth_token,
                'csrf' => $this->csrf_token
            ]
        ])->getBody();
    }

}