<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FlickrSearchRequest;
use App\Repositories\FlickrPhotosRepositories;
use GuzzleHttp\Promise as HttpPromise;

class FlickrController extends Controller
{
    /**
     * @var FlickrPhotosRepositories
     */
    protected $photos;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FlickrPhotosRepositories $photos)
    {
        $this->middleware('auth');

        $this->photos = $photos;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(FlickrSearchRequest $request)
    {
        // return $this->photos($request);
        // return $this->photos->sizes(29967627245);

        return view('home.index');
    }

    /**
     * Search photos matching some criteria.
     *
     * @param  FlickrSearchRequest $request
     * @return array
     */
    public function photos(FlickrSearchRequest $request): array
    {
        return $this->search($request->all());
    }

    /**
     * Search photos matching some criteria.
     *
     * @param  array  $input
     * @return array
     */
    protected function search(array $input): array
    {
        $photos = $this->photos->search($input);

        // Get the necessary related information.
        $promises = array_map(function(array $item): array {
            return [
                'info' => $this->photos->infoAsync($item['id']),
                'sizes' => $this->photos->sizesAsync($item['id']),
                'photo_id' => $item['id'],
            ];
        }, $photos);

        $responses = [
            'info' => HttpPromise\unwrap(array_column($promises, 'info')),
            'sizes' => HttpPromise\unwrap(array_column($promises, 'sizes')),
        ];

        return array_map(function(array $photo, $infoResp, $sizesResp): array {
            $info = $this->photos->transformPhoto(
                array_get(
                    unserialize(strval($infoResp->getBody())),
                    'photo'
                )
            );

            $sizes = $this->photos->transformSizes(
                array_get(
                    unserialize(strval($sizesResp->getBody())),
                    'sizes.size'
                )
            );

            // Get thumbnail from all sizes...
            $thumbnail = $sizes[round(count($sizes) / 3)];

            return compact('photo', 'thumbnail', 'info', 'sizes');
        }, $photos, $responses['info'], $responses['sizes']);
    }
}
