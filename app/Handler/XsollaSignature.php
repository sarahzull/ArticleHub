<?php

namespace App\Handler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\WebhookConfig;
use App\Exceptions\CustomInvalidWebhookSignature;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;

class XsollaSignature implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        $payload = $request->getContent(); 
        $secret = $config->signingSecret;
        
        $authorizationHeader = $request->header($config->signatureHeaderName);

        if (strpos($authorizationHeader, 'Signature ') === 0) {
            $signatureSent = substr($authorizationHeader, strlen('Signature '));
        } else {
            throw new CustomInvalidWebhookSignature();
        }

        $concatenated = $payload . $secret;
        $generatedSignature = sha1($concatenated);

        if (!hash_equals($generatedSignature, $signatureSent)) {
            throw new CustomInvalidWebhookSignature();
        }

        return true;
    }
}