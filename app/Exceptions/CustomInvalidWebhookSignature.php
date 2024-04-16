<?php

namespace App\Exceptions;

use Spatie\WebhookClient\Exceptions\InvalidWebhookSignature;

class CustomInvalidWebhookSignature extends InvalidWebhookSignature
{
    public function render($request)
    {
        return response()->json([
            'code' => 'INVALID_SIGNATURE',
            'message' => 'Invalid Signature'
        ], 400);
    }
}