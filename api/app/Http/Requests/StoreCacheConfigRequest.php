<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCacheConfigRequest extends FormRequest
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
            'app_id' => 'required|uuid|exists:apps,id',
            'custom' => 'required|boolean',
            'driver' => 'required_if:custom,true|string|in:redis,memcached,file',
            'host'   => 'required_if:custom,true|string',
            'port'   => 'required_if:custom,true|numeric',
        ];
    }
}
