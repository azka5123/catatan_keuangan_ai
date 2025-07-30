<?php

namespace App\Helpers;

use App\Models\ChatLogs;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
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

    static function balasPesanUser($nomorUser, $pesan, $replyTo = '', $session = 'default')
    {
        $apiWa = env('API_WA');
        if (Str::startsWith($nomorUser, '0')) {
            $nomorUser = Str::replaceFirst('0', '62', $nomorUser);
        }

        $pesan = preg_replace('/```json\s*.*?\s*```/is', '', $pesan);
        $payload = [
            "chatId" => $nomorUser,
            "reply_to" => $replyTo,
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

    public static function storeChatLog(string $nomorUser, string $userMessage, $aiResponse): void
    {
        $chatKey = "chat_history:{$nomorUser}";
        $timestamp = now()->toISOString();

        $chatEntry = [
            'user_message' => $userMessage,
            'ai_response' => $aiResponse,
            'timestamp' => $timestamp,
            'created_at' => now()->format('Y-m-d H:i:s')
        ];

        Redis::lpush($chatKey, json_encode($chatEntry));

        Redis::ltrim($chatKey, 0, 49);

        Redis::expire($chatKey, 30 * 24 * 60 * 60);
    }

    public static function getChatLogs(string $nomorUser, int $limit = 10): string
    {
        $chatKey = "chat_history:{$nomorUser}";

        // Ambil chat logs dari Redis (LRANGE untuk ambil range)
        $chatLogs = Redis::lrange($chatKey, 0, $limit - 1);

        if (empty($chatLogs)) {
            return "Belum ada riwayat chat sebelumnya.";
        }

        $formattedLogs = [];

        foreach ($chatLogs as $log) {
            $logData = json_decode($log, true);
            $formattedLogs[] = [
                'timestamp' => $logData['timestamp'],
                'user' => $logData['user_message'],
                'assistant' => $logData['ai_response']
            ];
        }

        $formattedLogs = array_reverse($formattedLogs);

        $chatHistory = "=== RIWAYAT CHAT SEBELUMNYA ===\n";
        foreach ($formattedLogs as $index => $log) {
            $chatHistory .= "Chat ke-" . ($index + 1) . " ({$log['timestamp']}):\n";
            $chatHistory .= "User: {$log['user']}\n";
            $chatHistory .= "Assistant: {$log['assistant']}\n";
            $chatHistory .= "---\n";
        }

        return $chatHistory;
    }

    public static function clearChatHistory(string $nomorUser): bool
    {
        $chatKey = "chat_history:{$nomorUser}";
        return Redis::del($chatKey) > 0;
    }

    static function balasPesanUserTelegram($chatIdTelegram, $pesan,$extraParams)
    {
        $telegramToken = env('TELEGRAM_BOT_TOKEN');
        $telegramApiUrl = "https://api.telegram.org/bot{$telegramToken}/sendMessage";

        $pesan = preg_replace('/```json\s*.*?\s*```/is', '', $pesan);

        $payload = [
            'chat_id' => $chatIdTelegram,
            'text' => $pesan,
            'parse_mode' => 'HTML',
        ];

        if($extraParams){
            $payload = array_merge($payload, $extraParams);
        }

        $response = Http::post($telegramApiUrl, $payload);

        if ($response->failed()) {
            Log::error('Failed to send Telegram message', [
                'response' => $response->body(),
                'payload' => $payload,
            ]);
        } else {
            Log::info('Success to send Telegram message', [
                'payload' => $payload
            ]);
        }
    }
}
