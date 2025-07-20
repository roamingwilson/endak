<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OtpCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'code',
        'type',
        'is_used',
        'expires_at'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime'
    ];

    /**
     * إنشاء رمز OTP جديد
     */
    public static function createOtp($phone, $type = 'registration', $expiresInMinutes = 10)
    {
        // إلغاء أي رموز سابقة لنفس الهاتف ونفس النوع
        self::where('phone', $phone)
            ->where('type', $type)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        // إنشاء رمز جديد
        $code = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);

        return self::create([
            'phone' => $phone,
            'code' => $code,
            'type' => $type,
            'expires_at' => Carbon::now()->addMinutes($expiresInMinutes)
        ]);
    }

    /**
     * التحقق من صحة رمز OTP
     */
    public static function verifyOtp($phone, $code, $type)
    {
        // البحث عن رمز OTP الصحيح وغير المنتهي
        $otp = self::where('phone', $phone)
                   ->where('code', $code)
                   ->where('type', $type)
                   ->where('expires_at', '>', now())
                   ->where('is_used', false)
                   ->first();

        if ($otp) {
            // تم التحقق بنجاح، تحديث الرمز ليكون مستخدماً
            $otp->update(['is_used' => true]);
            return true;
        }

        return false;
    }

    /**
     * التحقق من وجود رمز صالح
     */
    public static function hasValidOtp($phone, $type = 'registration')
    {
        return self::where('phone', $phone)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->exists();
    }

    /**
     * حذف الرموز المنتهية الصلاحية
     */
    public static function cleanupExpired()
    {
        return self::where('expires_at', '<', Carbon::now())->delete();
    }

    /**
     * الحصول على الرمز الحالي (للعرض فقط في التطوير)
     */
    public static function getCurrentOtp($phone, $type = 'registration')
    {
        return self::where('phone', $phone)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->value('code');
    }
}
