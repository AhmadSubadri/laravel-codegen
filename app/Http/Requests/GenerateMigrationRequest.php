<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateMigrationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'sql' => 'required|string|min:10',
        ];
    }

    public function messages()
    {
        return [
            'sql.min' => 'SQL statement is too short',
        ];
    }
}
