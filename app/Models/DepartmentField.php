<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentField extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'sub_department_id',
        'name',
        'name_ar',
        'name_en',
        'type',
        'options',
        'is_required',
        'input_group',
        'is_repeatable',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_repeatable' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function subDepartment()
    {
        return $this->belongsTo(SubDepartment::class);
    }
}
