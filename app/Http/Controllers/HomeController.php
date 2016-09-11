<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise as HttpPromise;
use App\Repositories\FlickrRepositories;
use App\Http\Requests\PhotosSearchRequest;

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
    public function index(PhotosSearchRequest $request)
    {
        $photos = array_map(function ($item) {
            $size = array_get($item, 'sizes');

            return $size[round(count($size) / 3)];

        }, $this->flickr->search($request->all()));

        return view('home.index', compact('photos'));
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
