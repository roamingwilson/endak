<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentField extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'name',
        'name_ar',
        'name_en',
        'type',
        'options',
        'is_required',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
