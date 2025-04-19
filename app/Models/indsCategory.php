<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class indsCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'industry_id'];
    public function industry() {
        return $this->belongsTo(industries::class);
    }

    public function subcategories() {
        return $this->hasMany(indSubCategory::class);
    }

}
