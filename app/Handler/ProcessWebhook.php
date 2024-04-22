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
        Log::info('Notification type received:', ['notification_type' => $notificationType]);

        switch ($notificationType) {
            case 'create_subscription':
                return WebhookService::createdSubscription($data);

            case 'update_subscription':
                return WebhookService::updatedSubscription($data);

            case 'cancel_subscription':
                return WebhookService::canceledSubscription($data);

            case 'non_renewal_subscription':
                return WebhookService::nonRenewalSubscription($data);

            case 'payment':
                return WebhookService::payment($data);

            default:
                Log::warning('Unsupported notification type received', ['notification_type' => $notificationType]);
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