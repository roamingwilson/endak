<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VanTruckService extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected static function booted(){
        static::addGlobalScope('status' , function(Builder $builder){
            $builder->where('status' , 'open');
        });
    }

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }
    public function vanTruck(){
        return $this->belongsTo(VanTruck::class , 'vantruck_id');
    }
    public function comments()
    {
        return $this->morphMany(GeneralComments::class, 'commentable');
    }
    public function orders()
    {
        return $this->morphMany(GeneralOrder::class, 'orderable');
    }
    public function images()
    {
        return $this->morphMany(GeneralImage::class, 'commentable');
    }
}
