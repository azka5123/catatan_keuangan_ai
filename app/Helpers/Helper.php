<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Helper
{
    static function sanitasiPesanUser(string $message, int $limit = 1000): string
    {
        $message = strip_tags($message);
        $message = preg_replace('/[^\PC\s]/u', '', $message);
        $message = preg_replace('/\s+/', ' ', trim($message));
        return Str::limit($message, $limit);
    }

    static function balasPesanUser($nomorUser, $pesan,$session = 'default')
    {
        $apiWa = env('API_WA');
        $payload = [
            "chatId" => $nomorUser,
            "reply_to" => null,
            "text" => $pesan,
            "linkPreview" => true,
            "linkPreviewHighQuality" => false,
            "session" => $session
        ];
        $response = Http::post($apiWa . '/sendText', $payload);
        if ($response->failed()) {
            Log::error('Failed to send WhatsApp message', [
                'response' => $response->body(),
                'payload' => $payload,
            ]);
        }else{
            LOG::info('Success to send WhatsApp message');
        }
    }
}
