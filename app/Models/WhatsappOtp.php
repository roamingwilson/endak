<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappOtp extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'otp_code',
        'expires_at',
    ];

    public $timestamps = true;
}
