<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Governements extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_ar',
        'name_en',
        'country_id'
    ];

    public function country(){
        $this->belongsTo(Country::class);
    }
}
