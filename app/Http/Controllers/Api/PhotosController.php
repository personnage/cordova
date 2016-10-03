<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\Photo;
use App\Jobs\ProcessingExternalPhoto;
use App\Http\Requests\PhotoCreateRequest;
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
     * sort=
     * page=
     * per_page=
     * search=
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $photos = Photo::query();

        // Apply filter by title and/or description.
        if ($request->has('search')) {
            $photos = $photos->search($request->input('search'));
        }

        $photos = $photos->sort((string) $request->input('sort'))->with('tags');
        $photos = $photos->simplePaginate($request->input('per_page') ?? 20);

        return $photos;
    }

    /**
     * Creates a new user. Note only administrators can create new users.
     *
     * @param  PhotoCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PhotoCreateRequest $request)
    {
        $photo = $this->createPhotoInstance($request->all());

        $photo->owner()->associate(auth()->user());

        $photo->save();

        $tags = $this->createTags(explode(',', $request->tags));

        $photo->tags()->sync($tags->pluck('id')->toArray());

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

    protected function transformCollection($users, $extended = false)
    {
        //
    }

    protected function transform($user)
    {
        return [
            //
        ];
    }

    protected function transformExtended($user)
    {
        return [
            //
        ];
    }

    protected function createPhotoInstance(array $data): Photo
    {
        return new Photo([
            'location' => $data['location'],

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
        }

        return collect($tags);
    }
}
