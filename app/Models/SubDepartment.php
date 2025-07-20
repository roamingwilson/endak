<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDepartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar', 'name_en', 'department_id', 'image', 'description_ar', 'description_en', 'status'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function fields()
    {
        return $this->hasMany(DepartmentField::class);
    }

    public function services()
    {
        return $this->hasMany(Services::class);
    }
}
