<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use DB;
use App\Models\Tag;
use App\Models\Photo;
use App\Models\PhotoLocation;
use App\Jobs\ProcessingExternalPhoto;
use App\Http\Requests\PhotoCreateRequest;
use App\Http\Requests\PhotosSearchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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
     * tags=
     * tag_mode=
     *
     * include_tags=true
     * include_owner=false
     * include_location=yes
     *
     * sort=
     * page=
     * per_page=
     * search=
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PhotosSearchRequest $request)
    {
        // 1) Если получен список тегов, выполним поиск тегов
        // if ($request->has('tags')) {
        //     $tagsIds = Tag::select('id')->search(explode(',', $request->tags))->pluck('id');
        // }

        // Create fresh builder.
        $photos = Photo::query();

        // Apply filter by title and/or description.
        if ($request->has('search')) {
            $photos = $photos->search($request->input('search'));
        }

        if ($request->include_tags = (bool) $request->include_tags) {
            $photos = $photos->with('tags');
        }

        if ($request->include_location = (bool) $request->include_location) {
            $photos = $photos->with('location');
        }

        $photos = $photos->sort((string) $request->input('sort'));

        $photos = $photos->simplePaginate($request->input('per_page') ?? 20);

        // return $photos;
        return $this->transformPhotos($photos->items(), [
            'include_tags' => $request->include_tags,
            'include_location' => $request->include_location,
        ]);
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

    protected function transformPhotos(array $photos, array $options = []): array {
        return array_map(function (Photo $photo) use($options) : array {
            $item = [
                'id' => $photo['id'],
                'owner_id' => $photo['user_id'],

                'label' => $photo['label'],
                'server' => $photo['server'],

                'title' => $photo['title'],
                'description' => $photo['description'],

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

            if (isset($options['include_location']) && $options['include_location']) {
                $item['location'] = $photo->location;
            }

            return $item;
        }, $photos);
    }

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
