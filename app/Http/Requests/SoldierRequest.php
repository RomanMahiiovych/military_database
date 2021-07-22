<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SoldierRequest extends FormRequest
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
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'date_of_entry' => 'required|date_format:d.m.Y',
            'email' => 'required|string|email|max:255|unique:soldiers',
            'phone_number' => ['required', 'regex:/(380)[0-9]{9}/'],
            'salary' => 'required|min:0|max:500000',
        ];
    }
}
