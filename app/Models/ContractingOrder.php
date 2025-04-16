<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractingOrder extends Model
{
    protected $guarded = [];
    public function getImageUrlAttribute()
    {
        return asset( 'storage/' . $this->image);
    }
    public function customer_contracting()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
    
    public function service_provider_contracting()
    {
        return $this->belongsTo(User::class, 'service_provider_id', 'id');
    }
    
    public function service_contracting()
    {
        return $this->belongsTo(ContractingService::class, 'service_id', 'id');
    }
}
