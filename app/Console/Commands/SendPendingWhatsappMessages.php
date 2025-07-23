<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendWhatsappMessageJob;
use Illuminate\Support\Facades\Log;

class SendPendingWhatsappMessages extends Command
{
    protected $signature = 'whatsapp:send-pending';
    protected $description = 'Execute all pending WhatsApp message jobs from the queue (for cron)';

    public function handle()
    {
        // جلب جميع الجوبس المعلقة من جدول jobs (queue)
        $pendingJobs = DB::table('jobs')->where('queue', 'default')->get();
        $count = 0;
        foreach ($pendingJobs as $job) {
            $payload = json_decode($job->payload, true);
            if (
                (isset($payload['displayName']) && strpos($payload['displayName'], 'SendWhatsappMessageJob') !== false) ||
                (isset($payload['data']['commandName']) && strpos($payload['data']['commandName'], 'SendWhatsappMessageJob') !== false)
            ) {
                $command = unserialize($payload['data']['command']);
                $delay = rand(1, 10); // من 1 ثانية إلى ساعة
                sleep($delay);
                $command->handle();
                DB::table('jobs')->where('id', $job->id)->delete();
                $count++;
            }
        }
        $this->info("Executed $count WhatsApp message jobs.");
    }
}
