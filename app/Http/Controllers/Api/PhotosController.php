<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use DB;
use App\Models\Tag;
use App\Models\User;
use App\Models\Photo;
use App\Models\PhotoLocation;
use App\Jobs\ProcessingExternalPhoto;
use App\Http\Requests\PhotoCreateRequest;
use App\Http\Requests\PhotosSearchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class PhotosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(PhotosSearchRequest $request): array
    {
        if ($request->has('tags')) {
            // Search photo by tags.
            $photos = Photo::whereHas('tags', function (Builder $query) use ($request) {
                $query->search(explode(',', $request->tags));
            });
        } else {
            // Create fresh builder.
            $photos = Photo::query();
        }

        // Apply filter by title and/or description.
        if ($request->has('search')) {
            $photos = $photos->search($request->input('search'));
        }

        if ($request->include_tags = (bool) $request->include_tags) {
            $photos = $photos->with('tags');
        }

        if ($request->include_owner = (bool) $request->include_owner) {
            $photos = $photos->with('owner');
        }

        if ($request->include_location = (bool) $request->include_location) {
            $photos = $photos->with('location');
        }

        $photos = $photos->sort((string) $request->input('sort'));
        $photos = $photos->simplePaginate($request->input('per_page') ?? 20);

        return [
            'current_page' => (int) $photos->currentPage(),
            'per_page' => (int) $photos->perPage(),
            'from' =>  (int) $photos->firstItem(),
            'to' =>  (int) $photos->lastItem(),

            'data' => $this->transformPhotos($photos->items(), [
                'include_tags' => $request->include_tags,
                'include_owner' => $request->include_owner,
                'include_location' => $request->include_location
            ]),
        ];
    }

    /**
     * Creates a new user. Note only administrators can create new users.
     *
     * @param  PhotoCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PhotoCreateRequest $request)
    {
        DB::beginTransaction();
        $photo = $this->createPhotoInstance($request->all());
        $photo->owner()->associate(auth()->user());
        $photo->save();

        $photo->location()->save(
            new PhotoLocation($request->only('location'))
        );

        $tags = $this->createTags(explode(',', $request->tags));

        $photo->tags()->sync($tags->pluck('id')->toArray());
        DB::commit();

        dispatch(new ProcessingExternalPhoto($photo));

        // 201
        return $photo;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * This is an idempotent function, calling this function for a non-existent
     * user id still returns a status code 200 OK.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
    }

    /**
     * Mark up user as soft deleted.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(int $id)
    {
        //
    }

    /**
     * Restore user after soft deleted.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(int $id)
    {
        //
    }

    /**
     * Transform raw output photos to pretty print.
     *
     * @param  array  $photos
     * @param  array  $options include_tags|include_owner|include_location
     * @return array
     */
    protected function transformPhotos(array $photos, array $options = []): array {
        return array_map(function (Photo $photo) use($options) : array {
            $item = [
                'id' => $photo['id'],
                'owner_id' => $photo['user_id'],

                'label' => $photo['label'],
                'server' => $photo['server'],

                'title' => $photo['title'],
                'description' => $photo['description'],

                'photo' => [
                    'width' => 200,  // stub
                    'height' => 300, // stub

                    'source' => sprintf(
                        'http://static.cordova.app/photos/%s/%d_%s.jpg',
                        $photo['server'],
                        $photo['id'],
                        $photo['label']
                    ),

                    'extension' => 'jpg', // stub
                ],

                'created_at' => [
                    'timestamp' => $photo['created_at']->timestamp,
                    'to_string' => $photo['created_at']->toDayDateTimeString(),
                ],

                'updated_at' => [
                    'timestamp' => $photo['updated_at']->timestamp,
                    'to_string' => $photo['updated_at']->toDayDateTimeString(),
                ],
            ];

            if (isset($options['include_tags']) && $options['include_tags']) {
                $item['tags'] = $this->transformTags($photo->tags->all());
            }

            if (isset($options['include_owner']) && $options['include_owner']) {
                $item['owner'] = $this->transformOwner($photo->owner);
            }

            if (isset($options['include_location']) && $options['include_location']) {
                $item['location'] = $photo->location;
            }

            return $item;
        }, $photos);
    }

    /**
     * Transform raw output tags to pretty print.
     *
     * @param  array  $tags
     * @return array
     */
    protected function transformTags(array $tags): array
    {
        return array_map(function (Tag $tag): array {
            return [
                'id' => $tag['id'],
                'author_id' => $tag['user_id'],

                'name' => $tag['name'],
                'slug' => $tag['slug'],

                'created_at' => [
                    'timestamp' => $tag['created_at']->timestamp,
                    'to_string' => $tag['created_at']->toDayDateTimeString(),
                ],

                'updated_at' => [
                    'timestamp' => $tag['updated_at']->timestamp,
                    'to_string' => $tag['updated_at']->toDayDateTimeString(),
                ],
            ];
        }, $tags);
    }

    /**
     * Transform raw output owner to pretty print.
     *
     * @param  User   $owner
     * @return array
     */
    protected function transformOwner(User $owner)
    {
        return [
            'id' => $owner['id'],
            'name' => $owner['name'],
            'username' => $owner['username'],
        ];
    }

    protected function createPhotoInstance(array $data): Photo
    {
        return new Photo([
            'title' => array_get($data, 'title', ''),
            'description' => array_get($data, 'description', ''),

            'provider' => array_get($data, 'provider', null),
            'extern_id' => array_get($data, 'extern_id', null),
        ]);
    }

    protected function createTags(array $items): Collection
    {
        $tags = [];

        foreach (array_filter($items) as $name) {
            $tags []= Tag::firstOrCreate([
                'name' => $name,
                'slug' => str_slug($name),
            ]);
            //! $tag->author()->associate(auth()->user());
        }

        return collect($tags);
    }
}
