<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use DB;
use App\Http\Requests\PhotoCreateRequest;
use App\Http\Requests\PhotosSearchRequest;
use App\Jobs\ProcessingExternalPhoto;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Photo;
use App\Models\PhotoLocation;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder;
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
        $this->middleware('auth:api');
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

        // Include advanced entity.
        $this->includeEntity($request, $photos);

        $photos = $photos->sort((string) $request->input('sort'));
        $photos = $photos->simplePaginate($request->input('per_page') ?? 20);

        return [
            'current_page' => (int) $photos->currentPage(),
            'per_page' => (int) $photos->perPage(),
            'from' =>  (int) $photos->firstItem(),
            'to' =>  (int) $photos->lastItem(),

            'data' => $this->transformPhotos($photos->items(), [
                'include_comments' => (bool) $request->include_comments,
                'include_likes' => (bool) $request->include_likes,
                'include_location' => (bool) $request->include_location,
                'include_owner' => (bool) $request->include_owner,
                'include_tags' => (bool) $request->include_tags,
            ]),
        ];
    }

    /**
     * Add answer additional entities, such as location or tags.
     *
     * @param  Request $request
     * @param  Builder $builder
     * @return void
     */
    protected function includeEntity(Request $request, Builder $builder)
    {
        foreach (['location', 'owner', 'tags'] as $entity) {
            if ((bool) $request->get($entity)) {
                $builder = $builder->with($entity);
            }
        }

        if ((bool) $request->include_comments) {
            $builder = $builder->with(['comments' => function (MorphMany $query) {
                $query->limit(10);
            }]);
        }

        if ((bool) $request->include_likes) {
            $builder = $builder->with(['likes' => function (MorphMany $query) {
                $query->limit(10);
            }]);

            $builder = $builder->withCount('likes');
        }
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

        // Execute processing to external photo: download, apply geocoding...
        dispatch(new ProcessingExternalPhoto($photo));

        DB::commit();

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
                        '%s/photos/%s/%d_%s.jpg',
                        env('APP_STATIC_URL'),
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

            if (isset($options['include_comments']) && $options['include_comments']) {
                $item['comments'] = [
                    'latest' => $this->transformComments($photo->comments->all()),
                ];
            }

            if (isset($options['include_likes']) && $options['include_likes']) {
                $item['likes'] = [
                    'count' => $photo->likes_count,
                    'latest' => $this->transformLikes($photo->likes->all()),
                ];
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

    protected function transformComments(array $comments): array
    {
        return array_map(function (Comment $comment): array {
            return [
                'id' => $comment['id'],
                'author_id' => $comment['user_id'],

                'content' => $comment['body'],

                'created_at' => [
                    'timestamp' => $comment['created_at']->timestamp,
                    'to_string' => $comment['created_at']->toDayDateTimeString(),
                ],

                'updated_at' => [
                    'timestamp' => $comment['updated_at']->timestamp,
                    'to_string' => $comment['updated_at']->toDayDateTimeString(),
                ],
            ];
        }, $comments);
    }

    protected function transformLikes(array $likes): array
    {
        return array_map(function (Like $like): array {
            return [
                'id' => $like['id'],
                'author_id' => $like['user_id']
            ];
        }, $likes);
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
    protected function transformOwner(User $owner): array
    {
        return [
            'id' => $owner['id'],
            'name' => $owner['name'],
            'username' => $owner['username'],
        ];
    }

    /**
     * Create new Photo instance and return.
     *
     * @param  array  $data
     * @return Photo
     */
    protected function createPhotoInstance(array $data): Photo
    {
        return new Photo([
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',

            'provider' => $data['provider'] ?? null,
            'extern_id' => $data['extern_id'] ?? null,
        ]);
    }

    /**
     * Create new tags if it's not exists.
     *
     * @param  array  $items
     * @return Collection
     */
    protected function createTags(array $items): Collection
    {
        $tags = [];

        foreach (array_filter($items) as $name) {
            $tag = Tag::firstOrNew([
                'name' => $name,
                'slug' => str_slug($name),
            ]);

            if (!$tag->exists || !$tag->author) {
                $tag->author()->associate(auth()->user());
                $tag->save();
            }

            $tags[]= $tag;
        }

        return collect($tags);
    }
}
