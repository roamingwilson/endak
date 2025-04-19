<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VanTruckOrder extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function getImageUrlAttribute()
    {
        return asset( 'storage/' . $this->image);
    }
    public function customer_maintenance()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function service_provider_maintenance()
    {
        return $this->belongsTo(User::class, 'service_provider_id', 'id');
    }

    public function service_maintenance()
    {
        return $this->belongsTo(VanTruckService::class, 'service_id', 'id');
    }
}
