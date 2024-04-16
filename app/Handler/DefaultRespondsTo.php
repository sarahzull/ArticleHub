<?php

namespace App\Handler;

use App\Services\UserService;
use App\Services\WebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\WebhookConfig;
use Symfony\Component\HttpFoundation\Response;
use Spatie\WebhookClient\WebhookResponse\RespondsToWebhook;

class DefaultRespondsTo implements RespondsToWebhook
{
    public function respondToValidWebhook(Request $request, WebhookConfig $config): Response
    {
        $notificationType = $request->input('notification_type');

        switch ($notificationType) {
            case 'user_validation':
                return WebhookService::userValidation($request);

            case 'created_subscription':
                // return $this->handleCreatedSubscription($request);
                return response()->json(['message' => 'ok']);

            case 'canceled_subscription':
                // return $this->handleCanceledSubscription($request);
                return response()->json(['message' => 'ok']);

            case 'payment':
                // return $this->handleCanceledSubscription($request);
                return response()->json(['message' => 'ok']);

            default:
                return response()->json([
                    'error' => [
                        'code' => '400',
                        'message' => 'Invalid or unsupported notification type.'
                    ]
                ], 400);
        }
        
        // return response()->json(['message' => 'not ok']);
    }

    protected function handleCreatedSubscription(Request $request)
    {
        // Implement logic to handle 'created_subscription' notification type
    }

    protected function handleCanceledSubscription(Request $request)
    {
        // Implement logic to handle 'canceled_subscription' notification type
    }
}