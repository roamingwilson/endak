<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FurnitureTransportationServiceProducts extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function product_item(){
        return $this->belongsTo(FurnitureTransportationProduct::class , 'product_id');
    }
    public function service(){
        return $this->belongsTo(FurnitureTransportationService::class , 'service_id');
    }
}
