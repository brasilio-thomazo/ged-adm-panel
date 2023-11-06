<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDatabaseConfigRequest extends FormRequest
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
            'custom'         => 'required|boolean',
        ];
        if ($this->custom) {
            $rules['driver'] = 'required|string|in:mysql,pgsql,sqlite,sqlsrv,mongodb';
            $rules['writer_host'] = 'required|string';
            $rules['writer_port'] = 'required|numeric';
            $rules['reader_host'] = 'required|string';
            $rules['reader_port'] = 'required|numeric';
        }

        return $rules;
    }
}
