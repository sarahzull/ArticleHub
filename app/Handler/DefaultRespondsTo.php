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
        Log::info("request", $request->all());

        $notificationType = $request->input('notification_type');

        switch ($notificationType) {
            case 'user_validation':
                $this->handleUserValidation($request);
                break;
            case 'created_subscription':
                $this->handleCreatedSubscription($request);
                break;
            case 'canceled_subscription':
                $this->handleCanceledSubscription($request);
                break;
        }
        
        return response()->json(['message' => 'ok']);
    }

    protected function handleUserValidation(Request $request)
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