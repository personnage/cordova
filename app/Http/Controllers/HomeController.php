<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise as HttpPromise;
use App\Repositories\FlickrRepositories;

class HomeController extends Controller
{

    /**
     * @var FlickrRepositories
     */
    protected $flickr;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FlickrRepositories $flickr)
    {
        $this->middleware('auth');

        $this->flickr = $flickr;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = array_map(function ($item) {
            $sizes = array_get($item, 'sizes.size');

            return $sizes[round(count($sizes) / 3)];

        }, $this->flickr->search());

        return view('poll', compact('images'));
    }

    public function unsplash()
    {
        $client = new HttpClient(['base_uri' => 'https://unsplash.it/']);
        $promises = [];
        $imagesSize = [];

        for ($i = 0; $i < 50; ++$i) {
            $w = mt_rand(150, 300);

            $promises []= $client->getAsync("$w/300?random");
            $imagesSize []= ['w' => $w];
        }

        $results = HttpPromise\unwrap($promises);

        $images = [];
        foreach ($results as $index => $response) {
            $body = $response->getBody();

            $images[$index] = base64_encode($body);
        }

        return view('home', compact('images', 'imagesSize'));
    }
}
