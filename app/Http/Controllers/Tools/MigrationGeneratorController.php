<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateMigrationRequest;
use App\Http\Services\Tools\MigrationGeneratorService;
use Illuminate\Support\Str;  // Ini sudah benar
use Illuminate\Support\Facades\Log;

class MigrationGeneratorController extends Controller
{
    protected $generatorService;

    public function __construct(MigrationGeneratorService $generatorService)
    {
        $this->generatorService = $generatorService;
    }

    public function index()
    {
        return view('tools.migration-generator');
    }

    public function generate(GenerateMigrationRequest $request)
    {
        try {
            $sql = $this->preprocessSql($request->sql);

            // Validate SQL structure
            if (!$this->isValidSql($sql)) {
                throw new \Exception("Invalid SQL structure detected");
            }

            $result = $this->generatorService->generateFromSql($sql);

            if ($request->boolean('generate_model')) {
                foreach ($result as $migration) {
                    if ($migration['type'] === 'migration') {
                        $columns = $this->generatorService->getTableColumns(
                            $migration['table'],
                            $sql
                        );
                        $result[] = [
                            'type' => 'model',
                            'filename' => Str::studly(Str::singular($migration['table'])) . '.php',
                            'code' => $this->generatorService->generateModel(
                                $migration['table'],
                                $columns
                            ),
                            'table' => $migration['table']
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'files' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('Migration generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'sql_sample' => Str::substr($sql ?? '', 0, 200)
            ]);

            return response()->json([
                'success' => false,
                'error' => config('app.debug')
                    ? $e->getMessage()
                    : 'Failed to generate migrations. Please check your SQL syntax.'
            ], 500);
        }
    }

    protected function preprocessSql(string $sql): string
    {
        // Remove comments
        $sql = preg_replace('/\/\*.*?\*\/|--.*$/ms', '', $sql);

        // Replace multiple spaces
        $sql = preg_replace('/\s+/', ' ', $sql);

        // Trim and ensure semicolon
        return trim($sql, " \t\n\r\0\x0B;") . ';';
    }

    protected function isValidSql(string $sql): bool
    {
        // Check basic structure
        if (!preg_match('/CREATE\s+TABLE/i', $sql)) {
            return false;
        }

        // Check balanced parentheses
        if (substr_count($sql, '(') !== substr_count($sql, ')')) {
            return false;
        }

        return true;
    }

    protected function normalizeSql(string $sql): string
    {
        // Remove comments
        $sql = preg_replace('/\/\*.*?\*\/|--.*?$/ms', '', $sql);
        // Replace multiple spaces with single space
        $sql = preg_replace('/\s+/', ' ', $sql);
        return trim($sql);
    }

    protected function generateModel(string $tableName, string $sql): ?string
    {
        try {
            $columns = $this->extractColumnsFromSql($tableName, $sql);
            return $this->generatorService->generateModel($tableName, $columns);
        } catch (\Exception $e) {
            Log::error("Model generation failed for table {$tableName}", [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    protected function extractColumnsFromSql(string $tableName, string $sql): array
    {
        $pattern = '/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?`?'
            . preg_quote($tableName, '/')
            . '`?\s*\((.*?)\)\s*(?:ENGINE|CHARSET|$)/is';

        if (!preg_match($pattern, $sql, $matches)) {
            throw new \Exception("Could not parse table definition for {$tableName}");
        }

        $columns = [];
        $columnDefs = preg_split('/,\s*(?![^()]*\))/', $matches[1]);

        foreach ($columnDefs as $def) {
            $def = trim($def);
            if (empty($def) || preg_match('/^(PRIMARY|FOREIGN|UNIQUE|KEY|CONSTRAINT)/i', $def)) {
                continue;
            }

            if (preg_match('/`?([^`\s]+)`?\s+([^\s(]+)(?:\(([^)]+)\))?/', $def, $colMatches)) {
                $columns[] = [
                    'name' => $colMatches[1],
                    'type' => strtolower($colMatches[2]),
                    'length' => $colMatches[3] ?? null,
                    'nullable' => !preg_match('/NOT\s+NULL/i', $def),
                    'default' => $this->extractDefaultValue($def),
                    'unsigned' => preg_match('/unsigned/i', $def),
                    'auto_increment' => preg_match('/AUTO_INCREMENT/i', $def)
                ];
            }
        }

        return $columns;
    }

    protected function extractDefaultValue(string $columnDef): ?string
    {
        if (preg_match('/DEFAULT\s+(?:\'([^\']+)\'|`([^`]+)`|([^\s,]+))/i', $columnDef, $matches)) {
            return $matches[1] ?? $matches[2] ?? $matches[3];
        }
        return null;
    }
}
