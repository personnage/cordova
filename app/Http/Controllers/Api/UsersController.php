<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\User;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserModifyRequest;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class UsersController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::query();

        if ($request->has('search')) {
            $users = $users->search($request->input('search'));
        }

        $users = $users->simplePaginate($request->input('per_page') ?? 50);

        if (count($users) > 0) {
            return $this->transformCollection($users->items(), Auth::user()->admin);
        }

        return response(null, 404);
    }

    /**
     * Creates a new user. Note only administrators can create new users.
     *
     * @param  UserCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $user = new User($request->all());
        $user->admin = $request->has('admin') && $request->admin;
        $user->password = bcrypt($request->password);
        $user->created_by_id = Auth::user();

        return response()->json(['ok' => $user->save()], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        if (! $user = User::find($id)) {
            return response(null, 404);
        }

        if (Auth::user()->admin) {
            return $this->transformExtended($user);
        } else {
            return $this->transform($user);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function card()
    {
        return $this->transformExtended(Auth::user());
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
        if (! $user = User::find($id)) {
            return response(null, 404);
        }

        $user->fill($request->all());
        $user->admin = $request->has('admin') && $request->admin;

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        return response()->json(['ok' => $user->save()], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserModifyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function modify(UserModifyRequest $request)
    {
        $user = $request->user();
        $user->fill($request->all());

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        return response()->json(['ok' => $user->save()], 200);
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
        if (Auth::user()->cannot('destroy-user')) {
            return response()->json(['ok' => false], 403);
        }

        $status = User::withTrashed()->findOrNew($id)->forceDelete();

        return response()->json(['ok' => $status], 200);
    }

    /**
     * Mark up user as soft deleted.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(int $id)
    {
        if (Auth::user()->cannot('delete-user')) {
            return response()->json(['ok' => false], 403);
        }

        if ($user = User::find($id)) {
            return response()->json(['ok' => $user->delete()], 200);
        }

        return response()->json(['ok' => false], 404);
    }

    /**
     * Restore user after soft deleted.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(int $id)
    {
        if (Auth::user()->cannot('restore-user')) {
            return response()->json(['ok' => false], 403);
        }

        if ($user = User::withTrashed()->find($id)) {
            return response()->json(['ok' => $user->restore()], 200);
        }

        return response()->json(['ok' => false], 404);
    }

    /**
     * Blocks the specified user. Available only for admin.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function block(int $id)
    {
        return $this->delete($id);
    }

    /**
     * Unblocks the specified user. Available only for admin.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unblock(int $id)
    {
        return $this->restore($id);
    }

    protected function transformCollection($users, $extended = false)
    {
        if ($users instanceof Collection) {
            $users = $users->toArray();
        }

        $method = $extended ? 'transformExtended' : 'transform';
        return array_map([$this, $method], $users);
    }

    protected function transform($user)
    {
        return [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'username' => $user['username'],
        ];
    }

    protected function transformExtended($user)
    {
        return [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'username' => $user['username'],
            'is_admin' => (bool) $user['admin'],
            'creator' => $user->creator ? $this->transformExtended($user->creator) : null,
            'created_at' => $user['created_at']->toDayDateTimeString(),
        ];
    }
}
