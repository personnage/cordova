<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserModifyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (bool) $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'max:255',
            'email' => 'email|max:255|unique:users,email,'.$this->user()->id,
            'username' => 'max:255|unique:users,username,'.$this->user()->id,
            'password' => 'min:6',
        ];
    }
}
