<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeavyEquipment extends Model
{
    use HasFactory;
    protected $fillable = ['name_ar', 'name_en', 'image','heavy_equip_id'];
    public function getImageUrlAttribute()
    {
        return asset( 'storage/' . $this->image);
    }
    public function departments()
    {
        return $this->morphMany(UserDepartment::class, 'commentable');
    }


}
