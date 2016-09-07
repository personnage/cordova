<?php

namespace App\Repositories;

use GuzzleHttp\Pool;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise as HttpPromise;

class FlickrRepositories
{
    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = new HttpClient(['base_uri' => 'https://api.flickr.com/services/rest/']);
    }

    protected function key()
    {
        return env('FLICKR_KEY');
    }

    protected function format()
    {
        return 'php_serial';
    }

    public function search($options = [])
    {
        $response = $this->httpClient->get('',
            [
                'query' => [
                    'api_key'  => $this->key(),
                    'format'   => $this->format(),
                    'method'   => 'flickr.photos.search',
                    'tags'     => '*',
                    'per_page' => '20',
                ]
            ]
        );

        $photos = unserialize($response->getBody());

        return array_map(function ($photo) {
            return $this->getSizes(array_get($photo, 'id'));

        }, array_get($photos, 'photos.photo'));
    }

    public function getSizes($photoId)
    {
        $response = $this->httpClient->get('',
            [
                'query' => [
                    'api_key'  => $this->key(),
                    'format'   => $this->format(),
                    'method'   => 'flickr.photos.getSizes',
                    'photo_id' => $photoId,
                ]
            ]
        );

        return unserialize($response->getBody());
    }
}
