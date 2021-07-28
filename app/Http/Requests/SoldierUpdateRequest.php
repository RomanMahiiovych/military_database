<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SoldierUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'min:2|max:255',
            'last_name' => 'min:2|max:255',
            'date_of_entry' => 'date_format:Y-m-d',
            'email' => 'string|email|max:255',
            'phone_number' => ['regex:/^([0-9\s\-\+\(\)]*)$/', 'max:19'],
            'salary' => 'min:0|max:500000',
            'photo' => ['mimes:jpeg,png', 'max:2048', 'dimensions:min_width=300,min_height=300,max_width=1000,max_height=1000'],
            'rank' => 'integer',
            'head' => 'integer',
        ];
    }

    public function messages() {
        return [
            'photo.*' => 'File format jpg, png up to 2 MB, the minimum size of 300x300px'
        ];
    }
}
