<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhotoCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !! $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'max:255',
            'description' => 'string',

            'tags' => 'string',

            'owner' => 'exists:users|id',

            'location' => 'required|array',
            'latitude' => 'in_array|location',
            'longitude' => 'in_array|location',

            'provider' => 'string',
            'extern_id' => 'unique:photos,extern_id,NULL,id,provider,flickr',
        ];
    }
}
