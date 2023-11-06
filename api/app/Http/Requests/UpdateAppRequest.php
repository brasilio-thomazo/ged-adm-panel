<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppRequest extends FormRequest
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
        $rules = [
            'use_domain' => 'required|boolean',
            'use_s3'     => 'required|boolean',
        ];

        if ($this->use_domain) {
            $rules['domain'] = 'required|regex:/^[a-z0-9\.-]$/|unique:apps,domain,' . $this->id;
        }

        if ($this->use_s3) {
            $rules['aws_region'] = 'required|regex:/^[a-z0-9-]+$/';
            $rules['aws_bucket'] = 'required|regex:/^[a-z0-9-]+$/';
        }

        return $rules;
    }
}
