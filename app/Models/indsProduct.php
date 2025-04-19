<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class indsProduct extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ind_sub_category_id', 'description', 'price'];

    public function subcategory() {
        return $this->belongsTo(indSubCategory::class);
    }

}
