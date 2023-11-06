<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCacheConfigRequest extends FormRequest
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
        $rules = [
            'custom' => 'required|boolean',
        ];
        if ($this->custom) {
            $rules['driver'] = 'required|string|in:redis,memcached,file';
            $rules['host']   = 'required|string';
            $rules['port']   = 'required|numeric';
        }
        return $rules;
    }
}
