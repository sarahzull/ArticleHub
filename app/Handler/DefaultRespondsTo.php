<?php

namespace App\Handler;

use App\Services\WebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\WebhookConfig;
use Symfony\Component\HttpFoundation\Response;
use Spatie\WebhookClient\WebhookResponse\RespondsToWebhook;

class DefaultRespondsTo implements RespondsToWebhook
{
    protected WebhookService $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function respondToValidWebhook(Request $request, WebhookConfig $config): Response
    {
        $notificationType = $request->input('notification_type');

        Log::info('Received webhook', [
            'type' => $notificationType,
            'payload' => $request->all()
        ]);

        switch ($notificationType) {
            case 'user_validation':
                return $this->webhookService->userValidation($request);

            case 'create_subscription':
                $this->webhookService->createdSubscription($request);
                return response()->json(['message' => 'ok']);

            case 'update_subscription':
                $this->webhookService->updatedSubscription($request);
                return response()->json(['message' => 'ok']);

            case 'cancel_subscription':
                $this->webhookService->canceledSubscription($request);
                return response()->json(['message' => 'ok']);

            case 'non_renewal_subscription':
                $this->webhookService->nonRenewalSubscription($request);
                return response()->json(['message' => 'ok']);

            case 'payment':
                $this->webhookService->payment($request);
                return response()->json(['message' => 'ok']);

            default:
                Log::warning('Unsupported webhook notification_type', [
                    'type' => $notificationType
                ]);

                return response()->json([
                    'error' => [
                        'code' => '400',
                        'message' => 'Invalid or unsupported notification type.'
                    ]
                ], 400);
        }
    }
}
