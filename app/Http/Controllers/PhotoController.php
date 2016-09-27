<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Photo;
use App\Jobs\ProcessingExternalPhoto;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests\PhotoCreateRequest;


class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Photo::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
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

        return $photo;
    }

    public function storeByFlickr(PhotoCreateRequest $request)
    {
        //
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
