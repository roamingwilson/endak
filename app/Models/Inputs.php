<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inputs extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_inputs', 'input_id', 'department_id');
    }
}
