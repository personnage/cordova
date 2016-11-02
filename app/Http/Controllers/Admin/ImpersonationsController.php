<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;

class ImpersonationsController extends Controller
{
    /**
     * Destroy impersonate session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $originalUser = auth()->user();

        $request->session()->regenerate();

        auth()->login($this->impersonator($request));

        $request->session()->forget('impersonator_id');

        // event(new WasImpersonated(auth()->user(), $originalUser));

        return redirect()->route('admin.user.show', $originalUser);
    }

    /**
     * Get impersonator user.
     *
     * @param  Request $request
     * @return \App\Models\User
     */
    private function impersonator(Request $request)
    {
        return User::findOrFail($request->session()->get('impersonator_id'));
    }
}
