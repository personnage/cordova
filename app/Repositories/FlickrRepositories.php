<?php

namespace App\Repositories;

use Carbon\Carbon;
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

    protected function key($default = null)
    {
        return [
            'api_key' => env('FLICKR_KEY', $default),
        ];
    }

    protected function format($default = null)
    {
        return [
            'format' => $default ?? 'php_serial',
        ];
    }

    protected function searchOptions()
    {
        return [
            'page' => 1,
            'per_page' => 25,

            'tags' => '*',
            'has_geo' => 1, // not edit
        ];
    }

    public function search(array $options = [])
    {
        $response = $this->httpClient->get('', [
            'query' => array_merge(
                $this->searchOptions(),
                $this->format(),
                $this->key(),
                $options,

                ['method' => 'flickr.photos.search']
            )
        ]);

        $photos = unserialize($response->getBody());

        $this->throwIfError($photos);

        $promises = array_map(function ($photo) {
            return [
                'info' => $this->getInfoAsync($photo['id']),
                'sizes' => $this->getSizesAsync($photo['id']),
            ];

        }, array_get($photos, 'photos.photo'));

        $responsesInfo = HttpPromise\unwrap(array_column($promises, 'info'));
        $responsesSizes = HttpPromise\unwrap(array_column($promises, 'sizes'));

        return array_map(function ($photo, $info, $sizes) {
            $photoInfo = unserialize($info->getBody());
            $photoSizes = unserialize($sizes->getBody());

            $this->throwIfError($photoInfo);
            $this->throwIfError($photoSizes);

            return array_merge(
                $this->transformPhoto($photo),
                ['info' => $this->transformInfo($photoInfo)],
                ['sizes' => $this->transformSizes($photoSizes)]
            );

        }, array_get($photos, 'photos.photo'), $responsesInfo, $responsesSizes);
    }

    protected function transformPhoto(array $photo)
    {
        return [
            'id' => (int) $photo['id'],
            'title' => $photo['title'],
        ];
    }

    public function getSizes(int $photoId)
    {
        $response = $this->getSizesAsync($photoId)->wait();

        $result = unserialize($response->getBody());

        $this->throwIfError($result);

        return $this->transformSizes($result);
    }

    public function getSizesAsync(int $photoId)
    {
        return $this->httpClient->getAsync('', [
            'query' => array_merge(
                $this->format(),
                $this->key(),
                ['photo_id' => $photoId],
                ['method' => 'flickr.photos.getSizes']
            )
        ]);
    }

    protected function transformSizes(array $sizes)
    {
        return array_map(function ($item) use ($sizes) {
            return [
                'label' => $item['label'],
                'media' => $item['media'],

                'width' => (int) $item['width'],
                'height' => (int) $item['height'],

                'url' => $item['url'],
                'source' => $item['source'],

                'can' => [
                    'blog' => (bool) $sizes['sizes']['canblog'],
                    'print' => (bool) $sizes['sizes']['canprint'],
                    'download' => (bool) $sizes['sizes']['candownload'],
                ],
            ];
        }, array_get($sizes, 'sizes.size'));
    }

    public function getInfo(int $photoId)
    {
        $response = $this->getInfoAsync($photoId)->wait();

        $result = unserialize($response->getBody());

        $this->throwIfError($result);

        return $result;
    }

    public function getInfoAsync(int $photoId)
    {
        return $this->httpClient->getAsync('', [
            'query' => array_merge(
                $this->format(),
                $this->key(),
                ['photo_id' => $photoId],
                ['method' => 'flickr.photos.getInfo']
            )
        ]);
    }

    protected function transformInfo($info)
    {
        $photo = $info['photo'];

        return [
            'id' => (int) $photo['id'],
            'updated_at' => Carbon::createFromTimestamp($photo['dates']['lastupdate']),
            'uploaded_at' => Carbon::createFromTimestamp($photo['dateuploaded']),

            'owner' => $photo['owner'],

            'title' => $photo['title']['_content'],
            'description' => $photo['description']['_content'],

            'location' => [
                'latitude' => $photo['location']['latitude'],
                'longitude' => $photo['location']['longitude'],

                'accuracy' => (int) $photo['location']['accuracy'],
            ],
        ];
    }

    protected function throwIfError(array $result)
    {
        if ('ok' != $result['stat']) {
            throw new \Exception($result['message'], $result['code']);
        }
    }
}
