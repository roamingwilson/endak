<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public static function getTemplate($key)
    {
        return optional(self::where('key', $key)->first())->template ?? 'مرحبا يوجد عميل يحتاج خدمة خاصة بقسم {department} علي موقع endak.net في مدينة {city} , قدم عرض الان';
    }

    public static function setTemplate($key, $value)
    {
        $template = self::firstOrNew(['key' => $key]);
        $template->template = $value;
        $template->save();
    }
}
