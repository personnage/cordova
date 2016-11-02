<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\{CreatePermissionRequest, EditPermissionRequest};
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permissions = Permission::filter($request->input('filter'));

        if ($request->has('search')) {
            $permissions = $permissions->search($request->input('search'));
        }

        $permissions = $permissions->sort($request->input('sort'));
        $permissions = $permissions->simplePaginate($request->input('per_page') ?? 15);

        return view('admin.permission.index', compact('permissions'))
            ->with('active', Permission::count())
            ->with('deleted', Permission::onlyTrashed()->count())
        ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = new Permission;

        return view('admin.permission.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePermissionRequest $request)
    {
        if (Permission::create($request->all())->wasRecentlyCreated) {
            return back()->with('notice', 'Permission was successfully created.');
        }

        return back()->with('alert', 'Error occurred. Permission was not created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $permission_id)
    {
        $permission = Permission::withTrashed()->with('roles')->findOrFail($permission_id);

        return view('admin.permission.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        return view('admin.permission.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditPermissionRequest $request, Permission $permission)
    {
        $permission->fill($request->all())->save();

        return back()->with('notice', 'Permission was successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $permission_id)
    {
        $permission = Permission::withTrashed()->findOrFail($permission_id);

        if ($permission->forceDelete()) {
            return redirect()->route('admin.permission.index', ['filter' => 'deleted'])
                ->with('notice', 'The permission is being deleted.');
        }

        return back()->with('alert', 'Error occurred. Permission was not deleted.');
    }

    public function delete(Permission $permission)
    {
        if ($permission->delete()) {
            return back()->with('notice', 'The permission is being deleted.');
        }

        return back()->with('alert', 'Error occurred. Permission was not deleted.');
    }

    public function restore($permission_id)
    {
        $permission = Permission::withTrashed()->findOrFail($permission_id);

        if ($permission->trashed()) {
            $permission->restore();

            return back()->with('notice', 'The permission restored.');
        }

        return back()->with('alert', 'Error occurred. Permission was not restored.');
    }
}
