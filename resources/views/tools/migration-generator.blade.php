@extends('layouts.tools')

@section('title', 'SQL to Migration Generator')

@section('content')
<div class="tool-container">
    <div class="tool-header">
        <h1>MySQL to Laravel Migration Generator</h1>
        <p>Convert SQL CREATE TABLE statements to Laravel migrations</p>
    </div>

    <div class="tool-body">
        <div class="row">
            <div class="col-md-6">
                <form id="migrationForm" method="POST">
                    <div class="form-group">
                        <label for="sqlInput">SQL CREATE TABLE Statements</label>
                        <textarea class="form-control" id="sqlInput" rows="15"
                            placeholder="CREATE TABLE `users` (...)"></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="generateModel" name="generate_model" checked>
                        <label class="form-check-label" for="generateModel">Generate Model Sekaligus</label>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-cog"></i> Generate Migrations
                    </button>
                </form>
            </div>

            <div class="col-md-6">
                <div id="resultContainer" class="result-container">
                    <div class="empty-state">
                        <i class="fas fa-code"></i>
                        <p>Generated migrations will appear here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@vite(['resources/js/tools/migration-generator.js'])