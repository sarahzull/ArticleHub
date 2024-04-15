<?php

namespace App\Handler;

use InvalidSignature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\WebhookConfig;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
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
            return false;
        }

        $concatenated = $payload . $secret;
        $generatedSignature = sha1($concatenated);

        if (hash_equals($generatedSignature, $signatureSent)) {
            return true;
        } else {
            throw new InvalidSignature('Invalid signature');
        }
    }
}