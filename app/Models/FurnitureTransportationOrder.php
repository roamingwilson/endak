<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FurnitureTransportationOrder extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function getImageUrlAttribute()
    {
        return asset( 'storage/' . $this->image);
    }
    public function customer_furniture_transportation()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
    
    public function service_provider_furniture_transportation()
    {
        return $this->belongsTo(User::class, 'service_provider_id', 'id');
    }
    
    public function service_furniture_transportation()
    {
        return $this->belongsTo(FurnitureTransportationService::class, 'service_id', 'id');
    }
    // public function offer_furniture_transportation()
    // {
    //     return $this->belongsTo(FurnitureTransportationService::class, 'service_id', 'id');
    // }
    
}
