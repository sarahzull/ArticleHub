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

    const PLANS_LIMIT = 10;

    public function __construct(XsollaClient $client, int $projectId, string $appUrl)
    {
        $this->client = $client;
        $this->projectId = $projectId;
        $this->appUrl = $appUrl;
    }

    public function createUserToken($user, $plan, $userSubId, bool $isPlanChanging = false)
    {
        $payload = [
            "purchase" => [
                "checkout" => ["currency" => "MYR", "amount" => (float) $plan->price],
                "subscription" => [
                    "plan_id" => $plan->external_id
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

        if ($isPlanChanging) {
            $payload["purchase"]["subscription"]["operation"] = "change_plan";
        }

        return $this->client->createToken($payload)->json();
    }

    public function getPlans () 
    {
        return $this->client->getPlans(self::PLANS_LIMIT);
    }

    public function cancelSubscription ($userId, $subscriptionId, $status)
    {
        /**
         * status: active, canceled, non_renewing
         */
        
        $payload = [
            "user_id" => (string) $userId,
            "status" => $status,
            // "cancel_subscription_payment" => true,
        ];

        $response = $this->client->cancelSubscription($userId, $subscriptionId, $payload);
        Log::info("updateSubscription", ['response' => $response]);

        return $response;
    }

    public function getSubscriptionByUserId($userId)
    {
        $params = [
            'limit' => 1,
            'user_id' => (string) $userId,
            // 'status[]' => 'active',
        ];

        return $this->client->getSubscriptionByUserId($params);
    }

    public function getRedirectUrl ($token)
    {
        return $this->client->getRedirectUrl($token);
    }
}