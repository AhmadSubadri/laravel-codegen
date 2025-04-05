<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class AIAsistenController extends Controller
{
    /**
     * Handle the incoming request to Groq API (LLaMA 3 Turbo).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function llamaAssistant(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:2000'
        ]);

        try {
            $prompt = $request->input('prompt');
            $apiKey = env('GROQ_API_KEY');

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama3-70b-8192',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Kamu adalah asisten AI yang ramah. Format semua contoh kode dengan tiga backtick dan sebutkan bahasa pemrogramannya. Contoh: ```ruby...```'
                    ],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
                'max_tokens' => 2048,
            ]);

            $result = $response->json();

            return response()->json([
                'prompt' => $prompt,
                'response' => $result['choices'][0]['message']['content'] ?? 'Tidak ada respon',
                'model' => 'llama3-70b-8192'
            ]);
        } catch (\Exception $e) {
            \Log::error('Groq API Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat memproses permintaan',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
