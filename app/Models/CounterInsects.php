<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounterInsects extends Model
{
    use HasFactory;
    protected $guarded =[];
    public function getImageUrlAttribute()
    {
        return asset( 'storage/' . $this->image);
    }
}
