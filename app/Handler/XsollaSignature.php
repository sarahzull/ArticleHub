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
    //     $signature = $request->header($config->signatureHeaderName);
    //     if (!$signature) {
    //         return false;
    //     }
    //     $signingSecret = $config->signingSecret;
    //     if (empty($signingSecret)) {
    //         throw WebhookFailed::signingSecretNotSet();
    //     }
    //     $computedSignature = hash_hmac('sha512', $request->getContent(), $signingSecret);
    //     return hash_equals($signature, $computedSignature);
    // }

    // public function isValid(Request $request, WebhookConfig $config): bool
    // {
    //     $payload = $request->getContent();
    //     $secret = $config->signingSecret;
    //     $signatureSent = $request->header($config->signatureHeaderName); // The signature sent in the request header

    //     // Concatenate the payload with the secret
    //     $concatenated = $payload . $secret;

    //     // Generate a SHA-1 hash of the concatenated string
    //     $generatedSignature = sha1($concatenated);

    //     // Compare the generated signature with the signature sent in the request
    //     return hash_equals($generatedSignature, $signatureSent);
    // }

    public function isValid(Request $request, WebhookConfig $config): bool
    {
        $payload = $request->getContent(); 
        $secret = $config->signingSecret;
        
        $authorizationHeader = $request->header($config->signatureHeaderName);
        Log::debug('Authorization header', ['header' => $authorizationHeader]);

        if (strpos($authorizationHeader, 'Signature ') === 0) {
            $signatureSent = substr($authorizationHeader, strlen('Signature '));
            Log::debug('Signature sent', ['signature' => $signatureSent]);
        } else {
            return false;
        }

        $concatenated = $payload . $secret;
        $generatedSignature = sha1($concatenated);
        Log::debug("generatedSignature", ['signature' => $generatedSignature]);

        Log::debug('Hash equals', ['result' => hash_equals($generatedSignature, $signatureSent)]);

        if (hash_equals($generatedSignature, $signatureSent)) {
            return true;
        } else {
            return false;
        }
    }
}