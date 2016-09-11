<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return view('home.index');
    }

    public function photos(PhotosSearchRequest $request)
    {
        return array_map(function ($photo) {
            // Convert Carbon instance to string datetime.
            $photo['info']['updated_at'] = $photo['info']['updated_at']->toDayDateTimeString();
            $photo['info']['uploaded_at'] = $photo['info']['uploaded_at']->toDayDateTimeString();

            return $photo;
        }, $this->search($request));
    }

    protected function search(PhotosSearchRequest $request)
    {
        return array_map(function ($photo) {
            $size = array_get($photo, 'sizes');

            $photo['item'] = $size[round(count($size) / 3)];

            return $photo;

        }, $this->flickr->search($request->all()));
    }
}
