<?php declare(strict_types=1);

namespace App\Repositories;

use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Client as HttpClient;

class FlickrPhotosRepositories extends FlickrRepositories
{
    protected function resolveMethod(string $name): string
    {
        return join('.', ['flickr', 'photos', $name]);
    }

    protected function searchOptions(array $options = []): array
    {
        return array_merge([
            // A comma-delimited list of tags.
            // Photos with one or more of the tags listed will be returned.
            // You can exclude results that match a term by prepending it with a - character.
            // 'tags' => '*',

            // Either 'any' for an OR combination of tags, or 'all' for an AND combination.
            // Defaults to 'any' if not specified.
            // 'tag_mode' => 'any',

            // A free text search.
            // Photos who's title, description or tags contain the text will be returned.
            // You can exclude results that match a term by prepending it with a - character.
            // 'text' => '',

            // The license id for photos (for possible values see the flickr.photos.licenses.getInfo method).
            // Multiple licenses may be comma-separated.
            'license' => '4,6,5,7,9,10',

            // Content Type setting:
            // 1 for photos only.
            // 2 for screenshots only.
            // 3 for 'other' only.
            // 4 for photos and screenshots.
            // 5 for screenshots and 'other'.
            // 6 for photos and 'other'.
            // 7 for photos, screenshots, and 'other' (all).
            'content_type' => 4,

            // Filter results by media type. Possible values are all (default), photos or videos
            'media' => 'photos',

            // Any photo that has been geotagged, or if the value is "0" any photo that has not been geotagged.
            'has_geo' => 1,


            // Any photo that has been geotagged, or if the value is "0" any photo that has not been geotagged.
            'per_page' => 25,

            // The page of results to return. If this argument is omitted, it defaults to 1.
            // 'page' => 1,
        ], $options);
    }

    public function search(array $options = []): array
    {
        $promise = $this->searchAsync($options);

        return $this->transformPhotos(
            array_get($this->unwrapResponse($promise->wait()), 'photos.photo')
        );
    }

    public function searchAsync(array $options = []): Promise
    {
        $query = $this->buildQuery(
            array_merge($this->searchOptions($options), $this->method('search'))
        );

        return $this->async($query);
    }

    public function transformPhotos(array $photos): array
    {
        return array_map(function (array $item): array {
            return [
                'id' => (int) $item['id'],
                'owner_id' => $item['owner'],

                'title' => (string) $item['title'],

                'visibility' => [
                    'ispublic' => (bool) $item['ispublic'],
                    'isfriend' => (bool) $item['isfriend'],
                    'isfamily' => (bool) $item['isfamily']
                ],

            ];
        }, $photos);
    }

    public function info(int $photoId): array
    {
        $promise = $this->infoAsync($photoId);

        return $this->transformPhoto(
            array_get($this->unwrapResponse($promise->wait()), 'photo')
        );
    }

    public function infoAsync(int $photoId): Promise
    {
        $query = $this->buildQuery([
            'method' => $this->resolveMethod('getInfo'),
            'photo_id' => $photoId,
        ]);

        return $this->async($query);
    }

    public function transformPhoto(array $photo): array
    {
        return [
            'id' => (int) $photo['id'],

            'owner' => [
                'id' => $photo['owner']['nsid'],
                'username' => $photo['owner']['username'],
                'realname' => $photo['owner']['realname'],
            ],



            'title' => $photo['title']['_content'],
            'description' => $photo['description']['_content'],

            'visibility' => [
                'ispublic' => (bool) $photo['visibility']['ispublic'],
                'isfriend' => (bool) $photo['visibility']['isfriend'],
                'isfamily' => (bool) $photo['visibility']['isfamily'],
            ],

            'usage' => [
                'candownload' => (bool) $photo['usage']['candownload'],
                'canblog' => (bool) $photo['usage']['canblog'],
                'canprint' => (bool) $photo['usage']['canprint'],
                'canshare' => (bool) $photo['usage']['canshare'],
            ],

            'tags' => array_map(function (array $tag): array {
                return [
                    'name' => $tag['raw'],
                    'slug' => $tag['_content'],
                    'owner_id' => $tag['author'],
                ];
            }, array_get($photo, 'tags.tag')),


            'location' => [
                'latitude' => $photo['location']['latitude'],
                'longitude' => $photo['location']['longitude'],

                'accuracy' => (int) $photo['location']['accuracy'],

                'locality' => $photo['location']['locality']['_content'] ?? '',
                'county' => $photo['location']['county']['_content'] ?? '',
                'region' => $photo['location']['region']['_content'] ?? '',
                'country' => $photo['location']['country']['_content'] ?? '',
            ],
        ];
    }

    public function sizes(int $photoId): array
    {
        $promise = $this->sizesAsync($photoId);

        return $this->transformSizes(
            array_get($this->unwrapResponse($promise->wait()), 'sizes.size')
        );
    }

    public function sizesAsync(int $photoId): Promise
    {
        $query = $this->buildQuery([
            'method' => $this->resolveMethod('getSizes'),
            'photo_id' => $photoId,
        ]);

        return $this->async($query);
    }

    public function transformSizes(array $sizes): array
    {
        return array_map(function (array $item): array {
            return [
                'width' => (int) $item['width'],
                'height' => (int) $item['height'],

                'label' => $item['label'],
                'media' => $item['media'],

                'source' => $item['source'],
            ];
        }, $sizes);
    }
}
