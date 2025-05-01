<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class notificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications; // استرجاع جميع الإشعارات للمستخدم
        return view('front_office.notify.show', compact('notifications'));
    }

    // تحديث حالة الإشعار كـ "مقروء"
    public function markAsRead($notificationId)
    {
        $user = Auth::user(); // الحصول على المستخدم الحالي

        // العثور على النوتيفيكاشن بناءً على الـ ID
        $notification = $user->notifications()->where('id', $notificationId)->first();

        // التحقق إذا كان الإشعار موجودًا
        if ($notification) {
            // تعيين قيمة "read_at" لتحديث حالة النوتيفيكاشن إلى "مقروء"
            $notification->markAsRead();
            if (request()->ajax()) {
                return response()->json(['success' => true]);
            }
            return redirect()->route('notifications.index')->with('success', 'تم قراءة الإشعار بنجاح');
        }

        // إذا لم يتم العثور على الإشعار
        return redirect()->route('notifications.index')->with('error', 'الإشعار غير موجود');
    }
    public function unreadNotifications()
{
    return $this->notifications->whereNull('read_at');
}
}
