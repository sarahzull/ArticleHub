<?php

namespace App\Handler;

use App\Services\UserService;
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
        Log::info('notification_type', ['type' => $notificationType]);

        switch ($notificationType) {
            case 'user_validation':
                Log::info('user_validation');
                $this->handleUserValidation($request);
                break;
            case 'created_subscription':
                Log::info('user_validation');
                $this->handleCreatedSubscription($request);
                break;
            case 'canceled_subscription':
                Log::info('user_validation');
                $this->handleCanceledSubscription($request);
                break;
            default:
                Log::warning('Unsupported notification type', ['type' => $notificationType]);
                return response()->json([
                    'error' => [
                        'code' => '400',
                        'message' => 'Invalid or unsupported notification type.'
                    ]
                ], 400);
        }
        
        // return response()->json(['message' => 'not ok']);
    }

    protected function handleUserValidation(Request $request): Response
    {
        $userData = $request->input('user');

        if (isset($userData['id'])) {
            $exist = UserService::checkUserExists($userData['id']);

            if ($exist) {
                return response()->json([
                    'code' => "200",
                    'message' => 'user exists.'
                ], 200);
            } else {
                return response()->json([
                    'error' => [
                        'code' => "400",
                        'message' => 'user not found.'
                    ]
                ], 400);
            }
        }

        return response()->json([
            'error' => [
                'code' => "400",
                'message' => 'user id is required.'
            ]
        ], 400);
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