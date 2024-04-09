<?php

namespace App\Handler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\WebhookConfig;
use Symfony\Component\HttpFoundation\Response;

class DefaultRespondsTo implements RespondsToWebhook
{
    public function respondToValidWebhook(Request $request, WebhookConfig $config): Response
    {
        Log::info("request", $request->all());
        return response()->json(['message' => 'ok']);
    }
}