<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class XsollaService 
{
    public static function createUserToken($user, $plan_id)
    {
        $merchantId = Config::get('services.xsolla.merchant_id');
        $projectId = Config::get('services.xsolla.project_id');
        $apiKey = Config::get('services.xsolla.api_key');
        $url = Config::get('services.xsolla.api_url') . "merchants/" . $merchantId . "/token";

      // $payload = [
      //   "user" => [
      //       "id" => ["value" => (string) $user->id, "hidden" => true],
      //       "email" => ["value" => $user->email],
      //       "name" => ["value" => $user->name, "hidden" => false],
      //   ],
      //   "settings" => [
      //       "project_id" => (int) $projectId,
      //       "payment_method" => 1380,
      //       "currency" => "MYR",
      //   ],
      //   "purchase" => ["subscription" => ["plan_id" => (string) $plan_id]],
      // ];

        $payload = [
            "purchase" => [
                "checkout" => ["currency" => "MYR", "amount" => 10],
                "subscription" => [
                    "gift" => [
                        "recipient" => "test_recipient_v1",
                        "email" => "recipient_email@email.com",
                    ],
                ],
            ],
            "settings" => [
                "currency" => "MYR",
                "language" => "en",
                "project_id" => 258216,
                "mode" => "sandbox",
                "ui" => [
                    "components" => ["virtual_currency" => ["custom_amount" => true]],
                    "desktop" => [
                        "virtual_item_list" => [
                            "button_with_price" => true,
                            "layout" => "list",
                        ],
                    ],
                    "size" => "medium",
                ],
            ],
            "user" => [
                "country" => ["allow_modify" => true, "value" => "MY"],
                "age" => 12,
                "email" => ["value" => "samad@mail.com"],
                "id" => ["value" => "1"],
                "name" => ["value" => "Samad Ushuk"],
            ],
        ];

        $response = Http::withBasicAuth($merchantId, $apiKey)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->post($url, $payload);

        return $response->json();
    }
}