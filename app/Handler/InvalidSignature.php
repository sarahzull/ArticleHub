<?php

use Spatie\WebhookClient\Exceptions\InvalidWebhookSignature;

class InvalidSignature extends InvalidWebhookSignature
{
    public static function invalidHeader(): self
    {
        return new static('Invalid webhook signature header.');
    }

    public static function invalidSignature(string $payload): self
    {
        return new static("Invalid webhook signature for payload: {$payload}");
    }
}