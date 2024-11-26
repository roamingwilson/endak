<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounterInsectsOrder extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function getImageUrlAttribute()
    {
        return asset( 'storage/' . $this->image);
    }
    public function customer_counter_insects()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
    
    public function service_provider_counter_insects()
    {
        return $this->belongsTo(User::class, 'service_provider_id', 'id');
    }
    
    public function service_counter_insects()
    {
        return $this->belongsTo(CounterInsectsService::class, 'service_id', 'id');
    }
}
