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

    static function balasPesanUser($nomorUser, $pesan, $session = 'default')
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
        } else {
            LOG::info('Success to send WhatsApp message');
        }
    }

    public static function saveImage($image, $name, $path = '')
    {
        if ($image == null) {
            return null;
        }
        $extension = $image->getClientOriginalExtension();
        $filename = $name . '.' . $extension;
        $path = public_path('dist/img/' . $path);
        $image->move($path, $filename);
        return $filename;
    }

    public static function deleteImage($filename, $path = '')
    {
        $path = public_path('dist/assets/img/' . $path);
        if (file_exists($path . $filename)) {
            unlink($path . $filename);
        } else {
            // return false;
        }
    }
}
