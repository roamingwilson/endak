<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarWater extends Model
{
    use HasFactory;
    protected $guarded =[];
    public function getImageUrlAttribute()
    {
        return asset( 'storage/' . $this->image);
    }
    public function departments()
    {
        return $this->morphMany(UserDepartment::class, 'commentable');
    }
}
