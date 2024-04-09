<?php

namespace App\Handler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\Exceptions\WebhookFailed;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;

class XsollaSignature implements SignatureValidator
{
    // public function isValid(Request $request, WebhookConfig $config): bool
    // {
    //     $payload = $request->getContent(); 
    //     $secret = $config->signingSecret;
        
    //     $authorizationHeader = $request->header($config->signatureHeaderName);
    //     Log::debug('Authorization header', ['header' => $authorizationHeader]);

    //     if (strpos($authorizationHeader, 'Signature ') === 0) {
    //         $signatureSent = substr($authorizationHeader, strlen('Signature '));
    //         Log::debug('Signature sent', ['signature' => $signatureSent]);
    //     } else {
    //         return false;
    //     }

    //     $concatenated = $payload . $secret;
    //     $generatedSignature = sha1($concatenated);
    //     Log::debug("generatedSignature", ['signature' => $generatedSignature]);

    //     Log::debug('Hash equals', ['result' => hash_equals($generatedSignature, $signatureSent)]);

    //     if (hash_equals($generatedSignature, $signatureSent)) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public function isValid(Request $request, WebhookConfig $config): bool
    {
        $signature = $request->header($config->signatureHeaderName);
        if (! $signature) {
            return false;
        }
        $signature = trim(str_replace("sha1=", "", $signature));
        $signingSecret = $config->signingSecret;

        if (empty($signingSecret)) {
            throw WebhookFailed::signingSecretNotSet();
        }
        $computedSignature = hash_hmac('sha1', $request->getContent(), $signingSecret);
        return hash_equals($signature, $computedSignature);
    }
}