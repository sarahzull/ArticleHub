<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class XsollaService 
{
    public static function createUserToken($user, $plan, $items)
    {
        $merchantId = Config::get('services.xsolla.merchant_id');
        $projectId = Config::get('services.xsolla.project_id');
        $apiMerchantKey = Config::get('services.xsolla.api_key');
        $url = Config::get('services.xsolla.api_url') . "merchants/" . $merchantId . "/token";
        $appUrl = Config::get('app.url');

        $payload = [
            "purchase" => [
                "checkout" => ["currency" => "MYR", "amount" => (float) $plan->price],
                "subscription" => [
                    "plan_id" => $plan->external_id,
                    "trial_days" => 0,
                ],
            ],
            "settings" => [
                "currency" => "MYR",
                "language" => "en",
                "project_id" => $projectId,
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
                "return_url" => "$appUrl/api/v1/subscription/callback",
                "redirect_policy" => [
                    "redirect_button_caption" => "Back to Site",
                ],
            ],
            "user" => [
                "country" => ["allow_modify" => true, "value" => "MY"],
                "email" => ["value" => $user->email],
                "id" => ["value" => (string) $user->id],
                "name" => ["value" => $user->name],
            ],
        ];

        Log::info($payload);

        if ($items != [] && $items['change_plan'] === true) {
            $payload["purchase"]["subscription"]["operation"] = "change_plan";
        }

        $response = Http::withBasicAuth($merchantId, $apiMerchantKey)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->post($url, $payload);

        Log::info($response->json());

        return $response->json();
    }

    public static function getPlans ($limit) 
    {
        $merchantId = Config::get('services.xsolla.merchant_id');
        $projectId = Config::get('services.xsolla.project_id');
        $apiKey = Config::get('services.xsolla.api_key');
        $url = Config::get('services.xsolla.api_url') . "projects/" . $projectId . "/subscriptions/plans";

        $params = [
            'limit' => $limit ?? 10
        ];

        $response = Http::withBasicAuth($merchantId, $apiKey)
                        ->get($url, $params);

        return $response->json();
    }

    public static function cancelSubscription ($user_id, $subscription_id, $status)
    {
        //https://api.xsolla.com/merchant/v2/projects/{project_id}/users/{user_id}/subscriptions/{subscription_id}

        $merchantId = Config::get('services.xsolla.merchant_id');
        $projectId = (int) Config::get('services.xsolla.project_id');
        $merchantApiKey = Config::get('services.xsolla.merchant_api_key');
        $url = Config::get('services.xsolla.api_url') . "projects/" . $projectId . "/users/" . (string) $user_id . "/subscriptions/" . (int) $subscription_id;

        /**
         * status: active, canceled, non_renewing
         */
        
        $payload = [
            "user_id" => (string) $user_id,
            "status" => $status,
            // "cancel_subscription_payment" => true,
        ];

        $response = Http::withBasicAuth($merchantId, $merchantApiKey)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->put($url, $payload);

        $subscription = SubscriptionPlan::where('plan_id', $response['plan']['id'])->first();

        $user = SubscriptionUser::where('user_id', $user_id)
            ->where('subscription_plan_id', $subscription->id)
            ->where('status', 'active')
            ->update([
            'status' => $response['status'],
            'end_date' => Carbon::parse($response['date_end']),
            'updated_at' => now(),
        ]);

        return $response->json();
    }

    public static function getSubscriptionByUserId($user_id)
    {
        $merchantId = Config::get('services.xsolla.merchant_id');
        $merchantApiKey = Config::get('services.xsolla.merchant_api_key');
        $url = Config::get('services.xsolla.api_url') . "merchants/" . $merchantId . "/subscriptions";

        $params = [
            'limit' => 1,
            'user_id' => (string) $user_id,
            // 'status[]' => 'active',
        ];

        $response = Http::withBasicAuth($merchantId, $merchantApiKey)
                        ->get($url, $params);
                        
        return $response->json();
    }
}