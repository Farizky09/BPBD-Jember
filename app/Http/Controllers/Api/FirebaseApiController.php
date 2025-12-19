<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class FirebaseApiController extends Controller
{
    public static function getGoogleAccessToken()
    {

        $serviceAccountPath = storage_path('app/service-account.json');
        $jsonkey = json_decode(file_get_contents($serviceAccountPath), true);

        $now = time();
        $token = [
            'iss' => $jsonkey['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => $jsonkey['token_uri'],
            'iat' => $now,
            'exp' => $now + 3600,
        ];

        $jwt = \Firebase\JWT\JWT::encode($token, $jsonkey['private_key'], 'RS256');

        $client = new Client();
        $response = $client->post($jsonkey['token_uri'], [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        return $body['access_token'];

    }

    public static function sendFCMNotification(Request $request)
    {
        $accessToken = FirebaseApiController::getGoogleAccessToken();
        // return $accessToken;
        $projectId = env('MOBILE_APP_ID');
        $client = new Client();
        if ($request->input('token') == null) {
            $topic = ['topic' => 'all'];
        } else {
            $topic = ['token' => $request->input('token')];
        }

        $response = $client->post('https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'message' => array_merge($topic, [
                    'notification' => [
                        'title' => $request->input('title'),
                        'body' => $request->input('body'),
                    ],
                ]),
            ]
        ]);

        return response()->json(['status' => 'success', 'response' => json_decode($response->getBody(), true)]);
    }

}
