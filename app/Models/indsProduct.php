<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class indsProduct extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'ind_sub_category_id', 'description', 'price','inds_category_id','image'];

    public function subcategory() {
        return $this->belongsTo(indSubCategory::class);
    }
    public function category() {
        return $this->belongsTo(indsCategory::class);
    }



}
