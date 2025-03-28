<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateMigrationRequest;
use App\Http\Services\Tools\MigrationGeneratorService;

class MigrationGeneratorController extends Controller
{
    public function __construct(
        protected MigrationGeneratorService $generatorService
    ) {}

    public function index()
    {
        return view('tools.migration-generator');
    }

    public function generate(GenerateMigrationRequest $request)
    {
        try {
            $migrations = $this->generatorService->generateFromSql($request->sql);

            return response()->json([
                'success' => true,
                'migrations' => $migrations,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
