<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhotosSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        return !! auth()->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Include advanced information.
            'include_tags' => 'in:1,0',
            'include_owner' => 'in:1,0',
            'include_location' => 'in:1,0',

            'tags' => 'string',
            'tag_mode' => 'in:or', // and/or

            'page' => 'integer',
            'per_page' => 'integer|max:250',
        ];
    }
}
