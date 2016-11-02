<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin\Photos;

use App\Models\PhotoCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = PhotoCategory::filter($request->input('filter'));

        if ($request->has('search')) {
            $categories = $categories->search($request->input('search'));
        }

        $categories = $categories->sort($request->input('sort'));
        $categories = $categories->simplePaginate($request->input('per_page') ?? 15);

        return view('admin.photos.category.index', compact('categories'))
            ->with('active',  PhotoCategory::count())
            ->with('deleted', PhotoCategory::filter('deleted')->count())
        ;
    }

    public function create()
    {
        //
    }

    public function store()
    {
        //
    }

    public function edit()
    {
        //
    }

    public function update()
    {
        //
    }

    public function delete()
    {
        //
    }
}
