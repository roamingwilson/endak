<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyPreparationOrder extends Model
{
    use HasFactory;

    
    protected $guarded = [];
    public function getImageUrlAttribute()
    {
        return asset( 'storage/' . $this->image);
    }
    public function customer_party_preparation()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
    
    public function service_provider_party_preparation()
    {
        return $this->belongsTo(User::class, 'service_provider_id', 'id');
    }
    
    public function service_party_preparation()
    {
        return $this->belongsTo(PartyPreparationService::class, 'service_id', 'id');
    }
}
