<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatLogs extends Model
{
    protected $table = 'chat_logs';

    protected $fillable = [
        'nomor_user',
        'pesan_user',
        'respon_ai',
    ];
}
