<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFinaces extends Model
{
    protected $table = 'user_finances';

    protected $fillable = [
        'tanggal',
        'keterangan',
        'deskripsi',
        'nominal',
        'no_hp',
        'jenis',
    ];
}
