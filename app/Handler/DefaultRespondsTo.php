<?php

namespace App\Handler;

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
        Log::info("config", json_encode($config));
        return response()->json(['message' => 'ok']);
    }

    public function respondToInvalidWebhook(Request $request, WebhookConfig $config, $reason = 'Invalid signature'): Response
    {
        Log::warning("Invalid webhook request received", [
            'reason' => $reason,
            'data' => $request->all()
        ]);
        return response()->json(['message' => 'not ok', 'error' => $reason], 400);
    }
}