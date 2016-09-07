<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = User::find($this->route('user'));

        if (is_null($user)) {
            return [];
        }

        return [
            'name' => 'max:255',
            'email' => 'email|max:255|unique:users,email,'.$user->id,
            'username' => 'max:255|unique:users,username,'.$user->id,
            'password' => 'min:6',

            'admin' => 'boolean',
        ];
    }
}
