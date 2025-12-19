<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LandingApiPageController extends Controller
{
    public function KonsultasiGemini(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,svg|max:1048',
        ]);

        try {
            $image = $request->file('image');
            $base64Image = base64_encode(file_get_contents($image));
            $apiKey = env('GEMINI_API_KEY');

            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => "Berikan saran dan solusi yang tepat berdasarkan gambar bencana ini.Jawaban harus dalam format JSON dengan key: jenis, dampak, penanganan."
                            ],
                            [
                                'inlineData' => [
                                    'mimeType' => $image->getMimeType(),
                                    'data' => $base64Image
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            $response = Http::post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}",
                $payload
            );

            $data = $response->json();

            if (isset($data['candidates']) && isset($data['candidates'][0]['content']['parts'])) {
                $resultText = $data['candidates'][0]['content']['parts'][0]['text'];
                $cleanedText = preg_replace('/```json|```/', '', $resultText);
                $cleanedText = trim($cleanedText);
                $parsed = json_decode($cleanedText, true);

                if (is_array($parsed)) {
                    return response()->json($parsed, 200);
                }
                preg_match('/\{.*\}/s', $resultText, $matches);
                if (!empty($matches)) {
                    $jsonString = $matches[0];
                    $fallbackParsed = json_decode($jsonString, true);
                    if (is_array($fallbackParsed)) {
                        return response()->json($fallbackParsed, 200);
                    }
                }
                Log::warning('Gagal parsing JSON Gemini:', [
                    'original' => $resultText,
                    'cleaned' => $cleanedText,
                ]);

                return response()->json([
                    'message' => 'Gagal parsing JSON dari Gemini',
                    'raw' => $resultText
                ], 422);
            } else {
                return response()->json([
                    'message' => 'Invalid response structure from Gemini',
                    'raw' => $data
                ], 422);
            }
        } catch (\Exception $e) {
            Log::error('Konsultasi Gemini Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses konsultasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function konsultasiGeminiText(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:2048',
        ]);

        try {
            $apiKey = env('GEMINI_API_KEY');
            $userText = $request->input('text');
            $prompt = "Berikan jenis, dampak, dan penanganan yang tepat berdasarkan bencana ini. Jawaban harus dalam format JSON dengan key: jenis, dampak, penanganan.";
            $payload =  [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $prompt
                            ],
                            [
                                'text' => $userText
                            ]
                        ]
                    ]
                ]
            ];
            $response = Http::post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}",
                $payload
            );
            $data = $response->json();
            if (isset($data['candidates']) && isset($data['candidates'][0]['content']['parts'])) {
                $resultText = $data['candidates'][0]['content']['parts'][0]['text'];
                $cleanedText = preg_replace('/```json|```/', '', $resultText);
                $cleanedText = trim($cleanedText);
                $parsed = json_decode($cleanedText, true);

                if (is_array($parsed)) {
                    return response()->json($parsed, 200);
                }
                preg_match('/\{.*\}/s', $resultText, $matches);
                if (!empty($matches)) {
                    $jsonString = $matches[0];
                    $fallbackParsed = json_decode($jsonString, true);
                    if (is_array($fallbackParsed)) {
                        return response()->json($fallbackParsed, 200);
                    }
                }
                Log::warning('Gagal parsing JSON Gemini:', [
                    'original' => $resultText,
                    'cleaned' => $cleanedText,
                ]);

                return response()->json([
                    'message' => 'Gagal parsing JSON dari Gemini',
                    'raw' => $resultText
                ], 422);
            } else {
                return response()->json([
                    'message' => 'Invalid response structure from Gemini',
                    'raw' => $data
                ], 422);
            }
        } catch (\Throwable $th) {
            Log::error('Konsultasi Gemini Error:', ['error' => $th->getMessage()]);
            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses konsultasi',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function konsultasiDisaster(Request $request)
    {
        $type = $request->input('type');
        $search = $request->input('search');
        $query = Consultation::query()->with('consultations');

        if ($type) {
            $query->where('type', $type);
        }

        if ($search) {
            $query->whereHas('consultations', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $consultaions = $query->get();
        if ($consultaions->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada konsultasi yang ditemukan.'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $consultaions
        ], 200);
    }
}
