<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class GenerateMigrationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'sql' => [
                'required',
                'string',
                'min:10',
                function ($attribute, $value, $fail) {
                    $normalized = $this->normalizeSql($value);

                    if (!preg_match('/CREATE\s+TABLE/i', $normalized)) {
                        $fail('SQL must contain at least one valid CREATE TABLE statement.');
                        return;
                    }

                    if (substr_count($normalized, '(') !== substr_count($normalized, ')')) {
                        $fail('Unbalanced parentheses in SQL. Please check your syntax.');
                        return;
                    }

                    if (!preg_match('/CREATE\s+TABLE\s+.+?\(.+?\)/is', $normalized)) {
                        $fail('Invalid table definition format. Ensure proper parentheses usage.');
                    }
                }
            ],
            'generate_model' => 'sometimes|boolean'
        ];
    }

    public function messages()
    {
        return [
            'sql.required' => 'SQL statement is required',
            'sql.min' => 'SQL statement must be at least 10 characters',
            'sql.string' => 'SQL must be a text string',
        ];
    }

    protected function normalizeSql(string $sql): string
    {
        $sql = preg_replace('/\/\*.*?\*\/|--.*?$/ms', '', $sql);
        $sql = str_replace(["\r\n", "\r"], "\n", $sql);
        $sql = preg_replace('/\s+/', ' ', $sql);
        return trim($sql);
    }
}
