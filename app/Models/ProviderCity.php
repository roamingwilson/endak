<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'governement_id'
    ];

    /**
     * العلاقة مع المستخدم (مزود الخدمة)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع المدينة
     */
    public function governement()
    {
        return $this->belongsTo(Governements::class);
    }
}
