<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    private $geminiModel = 'gemini-2.5-flash';
    
    private function getGeminiApiKey()
    {
        return config('services.gemini.api_key');
    }

    public function ask(Request $request)
    {
        $userMessage = $request->input('message');

        if (!$userMessage) {
            return response()->json(['error' => 'Pesan tidak boleh kosong'], 400);
        }

        try {
            // Prompt sistem agar chatbot hanya fokus pada ZIS
            $systemInstruction = "Kamu adalah asisten digital ahli dalam sistem pengelolaan zakat, infak, dan sedekah (SIPZIS). 
            Jawablah pertanyaan pengguna hanya seputar zakat, infak, sedekah, lembaga amil, mustahik, muzakki, sistem informasi zakat, pembayaran digital zakat, dan hal yang relevan.
            Jika pertanyaan di luar konteks, tolong jawab dengan sopan bahwa kamu hanya bisa membantu seputar pengelolaan zakat, infak, dan sedekah.";

            // Gemini API
            $apiKey = $this->getGeminiApiKey();
            
            if (!$apiKey) {
                return response()->json(['error' => 'GEMINI_API_KEY tidak dikonfigurasi'], 500);
            }
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->geminiModel}:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $systemInstruction . "\n\nPertanyaan pengguna: " . $userMessage
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 2048,
                ],
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Extract text from Gemini response
                $text = '';
                if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                    $text = $responseData['candidates'][0]['content']['parts'][0]['text'];
                }

                return response()->json([
                    'choices' => [
                        [
                            'message' => [
                                'content' => $text,
                                'role' => 'assistant'
                            ]
                        ]
                    ]
                ]);
            } else {
                return response()->json([
                    'error' => 'Gagal mendapatkan respons dari Gemini API: ' . $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
