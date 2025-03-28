<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Services\Tools\MigrationGeneratorService;
use Illuminate\Support\Facades\File;

class GenerateMigrationCommand extends Command
{
    protected $signature = 'generate:migration 
                            {sql : SQL CREATE TABLE statement}
                            {--output= : Output directory}';

    protected $description = 'Generate Laravel migration from SQL';

    public function handle(MigrationGeneratorService $generator)
    {
        try {
            $migrations = $generator->generateFromSql($this->argument('sql'));

            $outputDir = $this->option('output') ?? database_path('migrations');

            foreach ($migrations as $migration) {
                $path = "{$outputDir}/{$migration['filename']}";
                File::put($path, $migration['code']);
                $this->info("Created: {$path}");
            }

            $this->info('Done!');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
