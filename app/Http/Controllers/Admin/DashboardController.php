<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stats = $this->stats();

        return view('admin.dashboard.index', compact('stats'));
    }

    /**
     * Make stats object for rendering to dashboard.
     *
     * @return @anonymous
     */
    protected function stats()
    {
        return new class {
            public function countUsers()
            {
                // or fetch from cache
                return User::count();
            }

            public function countRoles()
            {
                return Role::count();
            }

            public function countPermissions()
            {
                return Permission::count();
            }

            public function latestUser()
            {
                return User::latest()->get();
            }

            public function latestNews()
            {
                //
            }

            //
        };
    }
}
