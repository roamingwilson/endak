<?php

namespace App\Http\View\Composers;

use App\Models\Settings;
use App\Models\Conversation;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class HeaderComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $settings = Settings::first();
        $view->with('settings', $settings);

        if (Auth::check()) {
            $user = Auth::user();

            $conversations = Conversation::where('sender_id', $user->id)
                ->orWhere('recipient_id', $user->id)
                ->with(['sender', 'recipient'])
                ->orderBy('updated_at', 'desc')
                ->get();

            $notifications = $user->unreadNotifications;

            $view->with('user', $user)
                ->with('conversations', $conversations)
                ->with('notifications', $notifications);
        } else {
            // Ensure variables exist even for guests, to avoid errors.
            $view->with('user', null)
                ->with('conversations', collect())
                ->with('notifications', collect());
        }
    }
}
