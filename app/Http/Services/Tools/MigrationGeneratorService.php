<?php

namespace App\Http\Services\Tools;

use Illuminate\Support\Str;

class MigrationGeneratorService
{
    public function generateFromSql(string $sql): array
    {
        $tableStatements = preg_split('/;\s*(?=CREATE TABLE)/i', $sql);
        $migrations = [];

        foreach ($tableStatements as $statement) {
            $statement = trim($statement);
            if (empty($statement)) continue;

            if (preg_match('/CREATE TABLE `?([^`\s]+)`?\s*\((.+)\)/is', $statement, $matches)) {
                $migrations[] = $this->processTable($matches[1], $matches[2]);
            }
        }

        return $migrations;
    }

    private function processTable(string $tableName, string $columnsPart): array
    {
        return [
            'table' => $tableName,
            'code' => $this->generateMigrationCode($tableName, $columnsPart),
            'filename' => date('Y_m_d_His') . '_create_' . Str::snake($tableName) . '_table.php'
        ];
    }

    private function generateMigrationCode(string $tableName, string $columnsPart): string
    {
        $columns = $this->parseColumns($columnsPart);

        return '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create' . Str::studly($tableName) . 'Table extends Migration
{
    public function up()
    {
        Schema::create(\'' . Str::snake($tableName) . '\', function (Blueprint $table) {
' . implode("\n", $columns) . '
        });
    }

    public function down()
    {
        Schema::dropIfExists(\'' . Str::snake($tableName) . '\');
    }
}';
    }

    private function parseColumns(string $columnsPart): array
    {
        $columns = [];
        $columnLines = preg_split('/,\s*(?=[^)]*(?:\(|$))/', $columnsPart);

        foreach ($columnLines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            if ($column = $this->parsePrimaryKey($line)) {
                $columns[] = $column;
                continue;
            }

            if ($column = $this->parseForeignKey($line)) {
                $columns[] = $column;
                continue;
            }

            if ($column = $this->parseRegularColumn($line)) {
                $columns[] = $column;
            }
        }

        return $columns;
    }

    private function parsePrimaryKey(string $line): ?string
    {
        if (strpos($line, 'PRIMARY KEY') === false) {
            return null;
        }

        if (preg_match('/PRIMARY KEY\s*\(`?([^`)]+)`?\)/', $line, $matches)) {
            $column = $matches[1];
            if (stripos($line, 'auto_increment') !== false) {
                return $column === 'id'
                    ? "\t\t\$table->id();"
                    : "\t\t\$table->bigIncrements('{$column}');";
            }
            return "\t\t\$table->unsignedBigInteger('{$column}')->primary();";
        }

        return null;
    }

    private function parseForeignKey(string $line): ?string
    {
        if (strpos($line, 'FOREIGN KEY') === false) {
            return null;
        }

        if (preg_match('/FOREIGN KEY\s*\(`?([^`)]+)`?\)\s*REFERENCES\s*`?([^`\s.]+)`?\s*\(`?([^`)]+)`?\)/', $line, $matches)) {
            $column = $matches[1];
            $references = $matches[3];
            $on = $matches[2];

            if (Str::endsWith($column, '_id') && $references === 'id') {
                $relatedTable = Str::beforeLast($column, '_id');
                return "\t\t\$table->foreignId('{$column}')->constrained('{$on}');";
            }

            return "\t\t\$table->foreign('{$column}')->references('{$references}')->on('{$on}');";
        }

        return null;
    }

    private function parseRegularColumn(string $line): ?string
    {
        if (!preg_match('/`?([^`\s]+)`?\s+([^\s]+)\s*(?:\(([^)]+)\))?\s*(.*)/', $line, $matches)) {
            return null;
        }

        $name = $matches[1];
        $type = strtolower($matches[2]);
        $length = $matches[3] ?? null;
        $modifiers = $matches[4] ?? '';

        // Handle primary key auto-increment
        if ($name === 'id' && stripos($modifiers, 'auto_increment') !== false) {
            return "\t\t\$table->id();";
        }

        // Handle special columns
        if ($name === 'remember_token') {
            return "\t\t\$table->rememberToken();";
        }

        if ($name === 'created_at' || $name === 'updated_at') {
            return '';
        }

        $laravelType = $this->mapTypeToLaravel($type, $length);
        $laravelModifiers = $this->parseModifiers($modifiers);

        // Format kolom khusus
        if ($laravelType === 'bigInteger' && $name === 'id') {
            return "\t\t\$table->id();";
        }

        if ($laravelType === 'timestamp' && $name === 'deleted_at') {
            return "\t\t\$table->softDeletes();";
        }

        return "\t\t\$table->{$laravelType}('{$name}'{$this->formatLength($type,$length)}){$laravelModifiers};";
    }

    private function mapTypeToLaravel(string $type, ?string $length): string
    {
        $typeMap = [
            'int' => 'integer',
            'tinyint' => 'tinyInteger',
            'smallint' => 'smallInteger',
            'mediumint' => 'mediumInteger',
            'bigint' => 'bigInteger',
            'varchar' => 'string',
            'char' => 'char',
            'text' => 'text',
            'mediumtext' => 'mediumText',
            'longtext' => 'longText',
            'json' => 'json',
            'blob' => 'binary',
            'datetime' => 'dateTime',
            'timestamp' => 'timestamp',
            'date' => 'date',
            'time' => 'time',
            'float' => 'float',
            'double' => 'double',
            'decimal' => 'decimal',
            'boolean' => 'boolean',
            'enum' => 'enum',
        ];

        return $typeMap[$type] ?? 'string';
    }

    private function parseModifiers(string $modifiers): string
    {
        $result = '';

        // Handle nullable
        if (stripos($modifiers, 'not null') !== false) {
        } elseif (stripos($modifiers, 'null') !== false) {
            $result .= '->nullable()';
        }

        // Handle default values
        if (preg_match('/default\s+([^\s,]+)/i', $modifiers, $matches)) {
            $default = trim($matches[1], "'`");
            if ($default !== 'null') {
                $result .= "->default('{$default}')";
            }
        }

        if (stripos($modifiers, 'unsigned') !== false) {
            $result .= '->unsigned()';
        }

        if (stripos($modifiers, 'unique') !== false) {
            $result .= '->unique()';
        }

        return $result;
    }

    private function formatLength(string $type, ?string $length): string
    {
        if (!$length) return '';

        $type = $this->mapTypeToLaravel($type, $length);

        if (in_array($type, ['string', 'char', 'decimal', 'float', 'double', 'enum'])) {
            return ", {$length}";
        }

        return '';
    }
}
