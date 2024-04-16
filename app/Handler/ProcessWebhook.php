<?php

namespace App\Handler;

use App\Services\UserService;
use App\Services\WebhookService;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

//The class extends "ProcessWebhookJob" class as that is the class 
//that will handle the job of processing our webhook before we have 
//access to it.

class ProcessWebhook extends ProcessWebhookJob
{
    public function handle()
    {
        $response = json_decode($this->webhookCall, true);
        $data = $response['payload'];
        Log::info('webhook data', $data);
        $notificationType = $data['notification_type'];

        switch ($notificationType) {
            // case 'user_validation':
            //     return WebhookService::userValidation($data);

            case 'create_subscription':
                return response()->json(['message' => 'ok']);

            case 'cancel_subscription':
                return response()->json(['message' => 'ok']);

            case 'payment':
                return response()->json(['message' => 'ok']);

            default:
                return response()->json([
                    'error' => [
                        'code' => '400',
                        'message' => 'Invalid or unsupported notification type.'
                    ]
                ], 400);
        }
        
        http_response_code(200);
    }
}