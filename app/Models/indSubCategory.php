<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class indSubCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'inds_category_id'];
    public function category() {
        return $this->belongsTo(indsCategory::class);
    }

    public function products() {
        return $this->hasMany(indsProduct::class);
    }


}
