<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkerProfile extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|max:100|email|unique:workers,email,'.auth()->guard('worker')->id(),
            'password' => 'nullable|string|min:8',
            'photo' => 'nullable',
            'phone' => 'required|string|max:17',
            'location' => 'required|string|min:6'
        ];
    }
}
