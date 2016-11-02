<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Events\Impersonation\UserImpersonated;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ResetsPasswords;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::filter($request->input('filter'));

        if ($request->has('search')) {
            $users = $users->search($request->input('search'));
        }

        $users = $users->sort($request->input('sort'));
        $users = $users->simplePaginate($request->input('per_page') ?? 15);

        return view('admin.user.index', compact('users'))
            ->with('activeCount', User::count())
            ->with('adminsCount', User::admins()->count())
            ->with('deletedCount', User::onlyTrashed()->count())
        ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        list($user, $roles) = [new User, Role::all()];

        return view('admin.user.create', compact('user', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @event  \App\Events\User\WasCreated
     * @param  \App\Http\Requests\CreateUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $user = User::forceCreate([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt(str_random(10)),

            'admin' => (bool) $request->admin,
            'confirmed_at' => Carbon::now(),
            'created_by_id' => auth()->id(),
        ]);

        if ($user->wasRecentlyCreated) {
            $this->sendResetLinkEmail($request);

            // assign role(s) (force null to array)
            foreach ((array) $request->input('roles') as $role_id) {
                $user->assignRole(Role::findOrFail($role_id));
            }

            return back()->with('notice', 'User was successfully created.');
        }

        return back()->with('alert', 'Error occurred. User was not created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $user_id)
    {
        $user = User::withTrashed()->findOrFail($user_id);

        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();

        return view('admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @event  \App\Events\User\WasChanged
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User          $user
     * @return \Illuminate\Http\Response
     */
    public function update(EditUserRequest $request, User $user)
    {
        // The administrator cannot absolve themselves of responsibility.
        if ($request->has('admin') && auth()->id() !== $user->id) {
            $user->admin = (bool) $request->input('admin');
        }

        if (mb_strlen($request->input('password')) > 1) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->fill($request->except('password'))->save();

        $user->roles()->sync((array) $request->input('roles'));

        return back()->with('notice', 'User was successfully updated.');
    }

    /**
     * Force a confirmation.
     *
     * @event  \App\Events\User\Confirmed
     * @param  \App\Models\User               $user
     * @return \Illuminate\Http\Response
     */
    public function confirm(User $user)
    {
        if ($user->confirm()) {
            return back()->with('notice', 'Successfully confirmed.');
        }

        return back()->with('alert', 'Error occurred. User was not confirmed');
    }

    /**
     * Delete user forever.
     *
     * @event  \App\Events\User\WasDeleted
     * @param  \App\Models\User             $user_id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $user_id)
    {
        $user = User::withTrashed()->findOrFail($user_id);

        if ($user->forceDelete()) {
            return redirect()
                ->route('admin.user.index', ['filter' => 'deleted'])
                ->with('notice', 'The user is being deleted.')
            ;
        }

        return back()->with('alert', 'Error occurred. User was not deleted.');
    }

    /**
     * Mark up as deleted.
     *
     * @event  \App\Events\User\WasDeleted
     * @param  \App\Models\User          $user
     * @return \Illuminate\Http\Response
     */
    public function delete(User $user)
    {
        if ($user->delete()) {
            return back()->with('notice', 'The user is being deleted.');
        }

        return back()->with('alert', 'Error occurred. User was not deleted.');
    }

    /**
     * Rstore soft deleted user.
     *
     * @event  \App\Events\User\WasRestored
     * @param  int                           $user_id
     * @return \Illuminate\Http\Response
     */
    public function restore($user_id)
    {
        $user = User::withTrashed()->findOrFail($user_id);

        if ($user->trashed()) {
            $user->restore();

            return back()->with('notice', 'The user restored.');
        }

        return back()->with('alert', 'Error occurred. User was not restored.');
    }

    /**
     * Impersonate given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User          $user
     * @return \Illuminate\Http\Response
     */
    public function impersonate(Request $request, User $user)
    {
        $request->session()->put('impersonator_id', auth()->id());

        auth()->login($user);

        event(new UserImpersonated($user, auth()->user()));

        return redirect()->to($request->root());
    }
}
