<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappSender extends Model
{
    use HasFactory;
    protected $fillable = ['number', 'token', 'instance_id'];

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'whatsapp_sender_department');
    }
}
