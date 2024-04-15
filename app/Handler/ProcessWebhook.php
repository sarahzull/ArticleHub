<?php

namespace App\Handler;

use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

//The class extends "ProcessWebhookJob" class as that is the class 
//that will handle the job of processing our webhook before we have 
//access to it.

class ProcessWebhook extends ProcessWebhookJob
{
    public function handle()
    {
        Log::info("payload", $this->webhookCall);
        $response = json_decode($this->webhookCall, true);
        $data = $response['payload'];
        $type = $data['notification_type'];
    
        if ($type == 'user_validation') {

        } elseif ($type == 'created_subscription') {

        } elseif ($type == 'canceled_subscription') {

        }

        http_response_code(200);
    }
}