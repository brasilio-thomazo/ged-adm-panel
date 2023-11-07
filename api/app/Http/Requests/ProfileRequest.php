<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:150',
            'identity' => 'required|regex:/^[0-9]{11}([0-9]{3})?$/',
            'role' => 'required|string|max:70',
            'phone' => 'required|regex:/^[0-9]{2}(9?)[0-9]{8}$/',
            'email' => 'required|email|max:150',
        ];
    }
}
