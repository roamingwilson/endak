<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\CommentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCommentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $provider;
    protected $notificationData;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\User $provider
     * @param array $notificationData
     */
    public function __construct(User $provider, array $notificationData)
    {
        $this->provider = $provider;
        $this->notificationData = $notificationData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->provider->notify(new CommentNotification($this->notificationData));
    }
}
