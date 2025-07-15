<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWhatsappMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to, $message, $fromNumber, $token, $instance_id;

    public function __construct($to, $message, $fromNumber, $token, $instance_id)
    {
        $this->to = $to;
        $this->message = $message;
        $this->fromNumber = $fromNumber;
        $this->token = $token;
        $this->instance_id = $instance_id;
    }

    public function handle()
    {
        sendWhatsAppMessage($this->to, $this->message, $this->fromNumber, $this->token, $this->instance_id);
    }
}
