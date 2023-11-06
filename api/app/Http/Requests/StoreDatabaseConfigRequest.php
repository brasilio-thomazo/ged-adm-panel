<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDatabaseConfigRequest extends FormRequest
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
        if ($this->custom) {
            $rule = Rule::unique('database_configs')
                ->where('driver', $this->driver)
                ->where('writer_host', $this->writer_host)
                ->where('writer_port', $this->writer_port)
                ->where('reader_host', $this->reader_host)
                ->where('reader_port', $this->reader_port);
        } else {
            $driver = config('database.default');
            $section = 'database.connections.' . $driver;
            $rule = Rule::unique('database_configs')
                ->where('driver', $driver)
                ->where('writer_host', config($section . '.write.host'))
                ->where('writer_port', config($section . '.write.port'))
                ->where('reader_host', config($section . '.read.host'))
                ->where('reader_port', config($section . '.read.port'));
        }

        return [
            'app_id'         => 'required|uuid|exists:apps,id',
            'custom'         => 'required|boolean',
            'driver'         => 'required_if:custom,true|string|in:mysql,pgsql,sqlite,sqlsrv,mongodb',
            'writer_host'    => 'required_if:custom,true|string',
            'writer_port'    => 'required_if:custom,true|numeric',
            'reader_host'    => 'required_if:custom,true|string',
            'reader_port'    => 'required_if:custom,true|numeric',
            'database'       => ['required', 'regex:/^[a-z0-9_]+$/', $rule],
        ];
    }
}
