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
    private ?XsollaClient $client = null;
    private ?int $projectId = null;
    private ?string $appUrl = null;

    public function __construct(XsollaClient $client, int $projectId, string $appUrl)
    {
        $this->client = $client;
        $this->projectId = $projectId;
        $this->appUrl = $appUrl;
    }

    public function createUserToken($user, $plan, $items, $userSubId)
    {
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
                "project_id" => $this->projectId,
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
                "return_url" => "$this->appUrl/api/v1/subscription/callback?user_sub_id=$userSubId",
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

        if ($items != [] && $items['change_plan'] === true) {
            $payload["purchase"]["subscription"]["operation"] = "change_plan";
        }

        return $this->client->createToken($payload)->json();
    }

    public function getPlans (int $limit) 
    {
        return $this->client->getPlans($limit);
    }

    public function cancelSubscription ($user_id, $subscription_id, $status)
    {
        /**
         * status: active, canceled, non_renewing
         */
        
        $payload = [
            "user_id" => (string) $user_id,
            "status" => $status,
            // "cancel_subscription_payment" => true,
        ];
        Log::info("cancelSubscription", ['payload' => $payload]);

        $response = $this->client->cancelSubscription($user_id, $subscription_id, $payload);
        Log::info("cancelSubscription", ['response' => $response]);

        return $response;
    }

    public function getSubscriptionByUserId($user_id)
    {
        $params = [
            'limit' => 1,
            'user_id' => (string) $user_id,
            // 'status[]' => 'active',
        ];

        return $this->client->getSubscriptionByUserId($params);
    }
}