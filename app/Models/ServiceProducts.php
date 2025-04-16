<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProducts extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function product_item(){
        return $this->belongsTo(Product::class , 'product_id');
    }
    public function service(){
        return $this->belongsTo(Post::class , 'service_id');
    }
}
