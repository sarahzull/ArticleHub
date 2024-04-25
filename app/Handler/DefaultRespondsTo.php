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
    protected $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function respondToValidWebhook(Request $request, WebhookConfig $config): Response
    {
        $notificationType = $request->input('notification_type');

        switch ($notificationType) {
            case 'user_validation':
                return $this->webhookService->userValidation($request);

            case 'create_subscription':
                return response()->json(['message' => 'ok']);

            case 'canceled_subscription':
                return response()->json(['message' => 'ok']);

            case 'payment':
                return response()->json(['message' => 'ok']);

            case 'non_renewal_subscription':
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
}