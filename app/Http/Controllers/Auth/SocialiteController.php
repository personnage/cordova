<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Validator;
use Socialite;
use Carbon\Carbon;
use App\Models\User;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to provider authentication page.
     *
     * @param  string   $provider The provider name: "github", "facebook" etc.
     * @return Response
     */
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.
     *
     * @param  string   $provider The provider name: "google", "twitter" etc.
     * @return Response
     */
    public function handleProviderCallback(string $provider)
    {
        $user = $this->findOrCreateProviderUser(
            $provider,
            Socialite::driver($provider)->user()
        );

        Auth::login($user);

        if (method_exists($this, 'redirectPath')) {
            return redirect()->to($this->redirectPath());
        }

        return back();
    }

    /**
     * Find or create new user.
     *
     * @event  UserRegistered
     * @param  stirng         $provider
     * @param  SocialiteUser  $externUser
     * @return \App\Models\User
     */
    protected function findOrCreateProviderUser(string $provider, SocialiteUser $externUser)
    {
        $user = User::firstOrNew([
            'extern_uid' => $externUser->getId(),
            'provider' => $provider,
        ]);

        if ($user->exists) {
            return $user;
        }

        // Find the user in the table "users" if he has
        // passed the stage of registration.
        if ($userByEmail = User::whereEmail($externUser->getEmail())->first()) {
            // Bind account with provider id.
            // Replace old provider if exists.
            $userByEmail->forceFill([
                'provider' => $provider,
                'extern_uid' => $externUser->getId(),
            ])->save();

            return $userByEmail;
        }

        $this->validator($this->userInfo($externUser))->validate();

        // Create new user.
        $user->fill($this->userInfo($externUser));
        $user->password = bcrypt(str_random(10));
        $user->provider = $provider;
        $user->extern_uid = $externUser->getId();
        $user->save();

        return $user;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'max:255',
            'email' => 'required|email|max:255|unique:users',
            'username' => 'required|max:255|unique:users',
        ]);
    }

    protected function userInfo($user)
    {
        return [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'username' => $user->getNickname() ?? $user->getEmail(),
        ];
    }
}
