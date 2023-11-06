<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id'   => 'required|uuid|exists:clients,id',
            'application' => 'required|in:0,1',
            'path'        => 'required|regex:/^[a-z0-9-]+$/|unique:apps,path',
            'domain'      => 'required_if:use_domain,true|regex:/^[a-z0-9\.-]$/|unique:apps,domain',
            'use_domain'  => 'required|boolean',
            'use_s3'      => 'required|boolean',
            'aws_region'  => 'required_if:use_s3,true|regex:/^[a-z0-9-]+$/',
            'aws_bucket'  => 'required_if:use_s3,true|regex:/^[a-z0-9-]+$/',
        ];
    }
}
