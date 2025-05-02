<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'total', 'status'];

    public function items()
    {
        return $this->hasMany(ProductOrderitems::class);
    }
    public function user()
{
    return $this->belongsTo(User::class,'user_id');
}
}
