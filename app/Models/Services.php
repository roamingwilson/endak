<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'custom_fields' => 'array',
    ];
    // protected static function booted(){

    //     static::addGlobalScope('status' , function(Builder $builder){
    //         $builder->where('status' , 'open');
    //     });
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function departments()
    {
        return $this->belongsTo(\App\Models\Department::class, 'department_id');
    }
    public function comments()
    {
        return $this->morphMany(GeneralComments::class, 'commentable');
    }
    public function order()
    {
        return $this->hasOne(GeneralOrder::class, 'service_id');
    }
    public function images()
    {
        return $this->morphMany(GeneralImage::class, 'commentable');
    }
    public function productss()
    {
        return $this->belongsToMany(Product::class, 'furniture_transportation_service_products')
            ->withPivot('quantity', 'installation', 'disassembly')
            ->withTimestamps();
    }
    public function products()
    {
        return $this->hasMany(FurnitureTransportationServiceProducts::class, 'service_id', 'id');
    }
    public function department()
   {
       return $this->belongsTo(\App\Models\Department::class, 'department_id');
   }

    public function subDepartment()
    {
        return $this->belongsTo(SubDepartment::class, 'sub_department_id');
    }
}
