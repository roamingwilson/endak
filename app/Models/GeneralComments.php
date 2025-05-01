<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralComments extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function commentable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'service_provider');
    }

    // العميل
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
