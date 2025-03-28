<?php

namespace App\Http\Services\Tools;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateMigrationRequest;
use App\Http\Services\Tools\MigrationGeneratorService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class MigrationGeneratorService
{
    public function generateFromSql(string $sql): array
    {
        try {
            $sql = $this->normalizeSql($sql);
            $this->validateSql($sql);
            $tables = $this->extractTables($sql);

            $migrations = [];
            foreach ($tables as $table) {
                try {
                    $migrations[] = $this->createMigrationData($table);
                } catch (\Exception $e) {
                    Log::error("Migration generation failed for table {$table['name']}", [
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }

            if (empty($migrations)) {
                throw new \Exception("No valid migrations were generated");
            }

            return $migrations;
        } catch (\Exception $e) {
            Log::error('Migration generation failed', [
                'error' => $e->getMessage(),
                'sql_sample' => Str::substr($sql, 0, 200)
            ]);
            throw $e;
        }
    }

    protected function createMigrationData(array $table): array
    {
        return [
            'type' => 'migration',
            'table' => $table['name'],
            'filename' => $this->generateMigrationName($table['name']),
            'code' => $this->generateMigrationCode($table['name'], $table['columns'])
        ];
    }

    protected function validateSql(string $sql): void
    {
        if (!preg_match('/CREATE\s+TABLE/i', $sql)) {
            throw new \Exception("No CREATE TABLE statements found");
        }

        if (substr_count($sql, '(') !== substr_count($sql, ')')) {
            throw new \Exception("Unbalanced parentheses in SQL");
        }
    }

    public function getTableColumns(string $tableName, string $sql): array
    {
        $tables = $this->extractTables($sql);
        foreach ($tables as $table) {
            if ($table['name'] === $tableName) {
                return $table['columns'];
            }
        }
        throw new \Exception("Table {$tableName} not found in SQL");
    }

    public function generateModel(string $tableName, array $columns): string
    {
        $modelName = Str::studly(Str::singular($tableName));
        $fillable = [];
        $casts = [];
        $timestamps = false;

        foreach ($columns as $column) {
            if (!in_array($column['name'], ['id', 'created_at', 'updated_at'])) {
                $fillable[] = "'{$column['name']}'";
            }

            if (in_array($column['name'], ['created_at', 'updated_at'])) {
                $timestamps = true;
            }

            if (in_array($column['type'], ['datetime', 'timestamp'])) {
                $casts[$column['name']] = 'datetime';
            } elseif ($column['type'] === 'json') {
                $casts[$column['name']] = 'array';
            }
        }

        $fillableStr = implode(",\n        ", $fillable);
        $castsStr = $this->generateCastsString($casts);
        $timestampsCode = $timestamps ? '' : "\n    public \$timestamps = false;";

        return <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {$modelName} extends Model
{
    use HasFactory;

    protected \$table = '{$tableName}';

    protected \$fillable = [
        {$fillableStr}
    ];

    protected \$casts = [
        {$castsStr}
    ];{$timestampsCode}
}
PHP;
    }

    protected function extractTables(string $sql): array
    {
        // Normalize SQL first
        $sql = $this->normalizeSql($sql);

        // Enhanced pattern to handle more SQL variations
        $pattern = '/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?[`"]?([^`"\s]+)[`"]?\s*\(([\s\S]+?)\)\s*(?:ENGINE|CHARSET|;|$)/i';

        if (!preg_match_all($pattern, $sql, $matches, PREG_SET_ORDER)) {
            throw new \Exception("No valid CREATE TABLE statements found or invalid syntax");
        }

        $tables = [];
        foreach ($matches as $match) {
            try {
                $tableName = trim($match[1], '`"');
                $columnsDef = trim($match[2]);

                if (empty($columnsDef)) {
                    throw new \Exception("No columns defined for table {$tableName}");
                }

                $tables[] = [
                    'name' => $tableName,
                    'columns' => $this->parseColumns($columnsDef)
                ];
            } catch (\Exception $e) {
                Log::error("Table parsing failed: " . $e->getMessage());
                continue;
            }
        }

        if (empty($tables)) {
            throw new \Exception("Could not extract any valid tables from SQL");
        }

        return $tables;
    }

    protected function parseColumns(string $columnsDef): array
    {
        $columns = [];
        $lines = preg_split('/,\s*(?![^()]*\))/', $columnsDef);

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || preg_match('/^(PRIMARY|FOREIGN|UNIQUE|KEY|CONSTRAINT|INDEX)/i', $line)) {
                continue;
            }

            if (preg_match('/`?([^`\s]+)`?\s+([^\s(]+)(?:\(([^)]+)\))?/i', $line, $matches)) {
                $column = [
                    'name' => trim($matches[1], '`'),
                    'type' => strtolower($matches[2]),
                    'length' => isset($matches[3]) ? trim($matches[3]) : null,
                    'nullable' => !preg_match('/NOT\s+NULL/i', $line),
                    'default' => $this->extractDefaultValue($line),
                    'unsigned' => preg_match('/unsigned/i', $line),
                    'auto_increment' => preg_match('/AUTO_INCREMENT/i', $line)
                ];

                $columns[] = $column;
            }
        }

        return $columns;
    }

    protected function generateMigrationName(string $tableName): string
    {
        return date('Y_m_d_His') . '_create_' . Str::snake($tableName) . '_table.php';
    }

    protected function generateMigrationCode(string $tableName, array $columns): string
    {
        $schema = $this->generateSchema($columns);
        $tableName = Str::snake($tableName);

        return <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('{$tableName}', function (Blueprint \$table) {
{$schema}
        });
    }

    public function down()
    {
        Schema::dropIfExists('{$tableName}');
    }
};
PHP;
    }

    protected function generateSchema(array $columns): string
    {
        $lines = ["\$table->id();"];
        $hasTimestamps = false;

        foreach ($columns as $column) {
            if ($column['name'] === 'id') continue;

            if (in_array($column['name'], ['created_at', 'updated_at'])) {
                $hasTimestamps = true;
                continue;
            }

            $method = $this->mapColumnType($column['type']);
            $line = "\$table->{$method}('{$column['name']}'";

            if ($column['length'] && !in_array($method, ['text', 'date', 'datetime', 'time'])) {
                $line .= ', ' . $column['length'];
            }

            if ($column['unsigned']) {
                $line .= '->unsigned()';
            }

            if ($column['nullable']) {
                $line .= '->nullable()';
            }

            if ($column['default'] !== null) {
                $default = is_numeric($column['default'])
                    ? $column['default']
                    : "'" . addslashes($column['default']) . "'";
                $line .= "->default($default)";
            }

            if ($column['auto_increment']) {
                $line .= '->autoIncrement()';
            }

            $line .= ';';
            $lines[] = $line;
        }

        if ($hasTimestamps) {
            $lines[] = '$table->timestamps();';
        }

        return '            ' . implode("\n            ", $lines);
    }

    protected function mapColumnType(string $dbType): string
    {
        $map = [
            'int' => 'integer',
            'varchar' => 'string',
            'char' => 'char',
            'text' => 'text',
            'mediumtext' => 'mediumText',
            'longtext' => 'longText',
            'tinyint' => 'tinyInteger',
            'smallint' => 'smallInteger',
            'mediumint' => 'mediumInteger',
            'bigint' => 'bigInteger',
            'decimal' => 'decimal',
            'float' => 'float',
            'double' => 'double',
            'date' => 'date',
            'datetime' => 'datetime',
            'timestamp' => 'timestamp',
            'time' => 'time',
            'enum' => 'enum',
            'set' => 'set',
            'json' => 'json',
            'boolean' => 'boolean',
            'bit' => 'boolean'
        ];

        return $map[strtolower($dbType)] ?? 'string';
    }

    protected function generateCastsString(array $casts): string
    {
        if (empty($casts)) {
            return '';
        }

        $lines = [];
        foreach ($casts as $field => $type) {
            $lines[] = "'$field' => '$type'";
        }

        return implode(",\n        ", $lines);
    }

    protected function extractDefaultValue(string $columnDef): ?string
    {
        if (preg_match('/DEFAULT\s+(?:\'([^\']+)\'|`([^`]+)`|([^\s,]+))/i', $columnDef, $matches)) {
            return $matches[1] ?? $matches[2] ?? $matches[3];
        }
        return null;
    }

    protected function normalizeSql(string $sql): string
    {
        // Remove comments
        $sql = preg_replace('/\/\*.*?\*\/|--.*?$/ms', '', $sql);
        // Standardize line endings
        $sql = str_replace(["\r\n", "\r"], "\n", $sql);
        // Remove extra spaces
        $sql = preg_replace('/\s+/', ' ', $sql);
        return trim($sql);
    }
}
