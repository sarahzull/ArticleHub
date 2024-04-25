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
    protected $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function handle()
    {
        $response = json_decode($this->webhookCall, true);
        $data = $response['payload'];
        Log::info('webhook data', $data);
        $notificationType = $data['notification_type'];
        Log::info('Notification type received:', ['notification_type' => $notificationType]);

        switch ($notificationType) {
            case 'create_subscription':
                return $this->webhookService->createdSubscription($data);

            case 'update_subscription':
                return $this->webhookService->updatedSubscription($data);

            case 'cancel_subscription':
                return $this->webhookService->canceledSubscription($data);

            case 'non_renewal_subscription':
                return $this->webhookService->nonRenewalSubscription($data);

            case 'payment':
                return $this->webhookService->payment($data);

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