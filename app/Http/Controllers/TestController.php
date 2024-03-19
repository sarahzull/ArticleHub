<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\XsollaService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class TestController extends Controller
{

    private static function getHttp()
    {
        $auth = [
            'merchant_id' => Config::get('services.xsolla.merchant_id'),
            'api_key' => Config::get('services.xsolla.api_key'),
        ];

        $xsollaApiUrl = Config::get('services.xsolla.api_url');

        return Http::baseUrl($xsollaApiUrl)
            ->withBasicAuth($auth['merchant_id'], $auth['api_key'])
            ->asJson();
    }

    public function createToken()
    {
        $merchantId = Config::get('services.xsolla.merchant_id');
        $apiKey = Config::get('services.xsolla.api_key');
        $url = Config::get('services.xsolla.api_url') . "merchants/" . $merchantId . "/token";

        $payload = [
            "purchase" => [
                "checkout" => [
                    "currency" => "USD",
                    "amount" => 10,
                ],
                "subscription" => [
                    "gift" => [
                        "recipient" => "test_recipient_v1",
                        "email" => "recipient_email@email.com",
                    ],
                ],
            ],
            "settings" => [
                "currency" => "USD",
                "language" => "en",
                "project_id" => 258216,
                "ui" => [
                    "components" => [
                        "virtual_currency" => [
                            "custom_amount" => true,
                        ],
                    ],
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
                "country" => [
                    "allow_modify" => true,
                    "value" => "US",
                ],
                "age" => 19,
                "email" => [
                    "value" => "john.smith@mail.com",
                ],
                "id" => [
                    "value" => "user_2",
                ],
                "name" => [
                    "value" => "John Smith",
                ],
            ],
        ];

        $response = Http::withBasicAuth($merchantId, $apiKey)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->post($url, $payload);

        if ($response->successful()) {
            $data = $response->json();
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Request failed.'], $response->status());
        }
    }

    
    public function getSubscriptions ()
    {
        $merchantId = Config::get('services.xsolla.merchant_id');
        $apiKey = Config::get('services.xsolla.api_key');
        $url = Config::get('services.xsolla.api_url') . "merchants/" . $merchantId . "/subscriptions";

        $params = [
            'limit' => 10
        ];

        $user = User::find(2);
        $test = XsollaService::createUserToken($user, 291703);

        dd($test);
        

        $response = Http::withBasicAuth($merchantId, $apiKey)
                        ->get($url, $params);

        if ($response->successful()) {
            $data = $response->json();
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Request failed.'], $response->status());
        }
    }

    public function getPlans ()
    {
        $merchantId = Config::get('services.xsolla.merchant_id');
        $projectId = Config::get('services.xsolla.project_id');
        $apiKey = Config::get('services.xsolla.api_key');
        $url = Config::get('services.xsolla.api_url') . "projects/" . $projectId . "/subscriptions/plans";

        $params = [
            'limit' => 10
        ];

        $response = Http::withBasicAuth($merchantId, $apiKey)
                        ->get($url, $params);

        // foreach ($response->json() as $plan) {
        //     SubscriptionPlan::create([
        //         'plan_id' => $plan['id'],
        //         'name' => $plan['name']['en'],
        //         'description' => $plan['description']['en'],
        //         'price' => $plan['charge']['prices'][0]['amount'],
        //     ]);
        // }

        if ($response->successful()) {
            $data = $response->json();
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Request failed.'], $response->status());
        }
    }
}
