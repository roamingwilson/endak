<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanupReadNotifications extends Command
{
    protected $signature = 'notifications:cleanup-read';
    protected $description = 'احذف الإشعارات المقروءة بعد 48 ساعة من قراءتها';

    public function handle()
    {
        $deleted = DB::table('notifications')
            ->whereNotNull('read_at')
            ->where('read_at', '<', Carbon::now()->subHours(48))
            ->delete();

        $this->info("تم حذف $deleted إشعاراً مقروءاً أقدم من 48 ساعة.");
    }
}
