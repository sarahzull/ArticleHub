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
    public function handle(WebhookService $webhookService)
    {
        $response = json_decode($this->webhookCall, true);
        $data = $response['payload'];
        Log::info('webhook data', $data);
        $notificationType = $data['notification_type'];
        Log::info('Notification type received:', ['notification_type' => $notificationType]);

        switch ($notificationType) {
            case 'create_subscription':
                return $webhookService->createdSubscription($data);

            case 'update_subscription':
                return $webhookService->updatedSubscription($data);

            case 'cancel_subscription':
                return $webhookService->canceledSubscription($data);

            case 'non_renewal_subscription':
                return $webhookService->nonRenewalSubscription($data);

            case 'payment':
                return $webhookService->payment($data);
            
            case 'user_validation':
                return $webhookService->userValidation($data);

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