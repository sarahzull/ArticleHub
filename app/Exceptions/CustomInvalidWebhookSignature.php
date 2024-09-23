<?php

namespace App\Exceptions;

use Spatie\WebhookClient\Exceptions\InvalidWebhookSignature;

class CustomInvalidWebhookSignature extends InvalidWebhookSignature
{
    public function render($request)
    {
        return response()->json([
            'error' => [
              'code' => 'INVALID_SIGNATURE',
              'message' => 'Invalid signature'
            ]
        ], 400);
    }
}