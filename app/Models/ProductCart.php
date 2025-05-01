<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'inds_product_id', 'quantity'];

    public function product()
    {
        return $this->belongsTo(indsProduct::class, 'inds_product_id');
    }
}
