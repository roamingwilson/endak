<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->logo);
    }

    /**
     * Get the dynamic message template
     */
    public static function getMessageTemplate()
    {
        return optional(self::first())->message_template ?? 'مرحبا يوجد عميل يحتاج خدمة خاصة بقسم {department} علي موقع endak.net في مدينة {city} , قدم عرض الان';
    }

    /**
     * Update the dynamic message template
     */
    public static function setMessageTemplate($value)
    {
        $settings = self::first();
        if ($settings) {
            $settings->message_template = $value;
            $settings->save();
        }
    }
}
