<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    /**
     * تعليم جميع الرسائل كمقروءة للمستخدم الحالي
     */
        public function markAllAsRead()
    {
        $user = Auth::user();

        // تحديث جميع الرسائل المرسلة للمستخدم الحالي
        $updatedCount = Message::where('recipient_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        Log::info('Messages marked as read for user ' . $user->id . '. Updated: ' . $updatedCount);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'updated' => $updatedCount]);
        }

        return redirect()->back()->with('success', 'تم قراءة جميع الرسائل');
    }
}
