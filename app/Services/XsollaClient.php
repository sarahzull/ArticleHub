<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class XsollaClient
{
  private int $merchantId;
  private int $projectId;
  private string $apiMerchantKey;
  private string $apiBaseUrl;

  public function __construct(int $merchantId, int $projectId, string $apiMerchantKey, string $apiBaseUrl)
  {
    $this->merchantId = $merchantId;
    $this->projectId = $projectId;
    $this->apiMerchantKey = $apiMerchantKey;
    $this->apiBaseUrl = $apiBaseUrl;
  }
  
  public function createToken(array $payload): \Illuminate\Http\Client\Response
  {
    $apiUrl = $this->apiBaseUrl . "merchants/" . $this->merchantId . "/token";

    return Http::withBasicAuth($this->merchantId, $this->apiMerchantKey)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->post($apiUrl, $payload);
  }

  public function getPlans(int $limit): array
  {
    $apiUrl = $this->apiBaseUrl . "projects/" . $this->projectId . "/subscriptions/plans";

    $params = [
      'limit' => $limit ?? 10
    ];

    return Http::withBasicAuth($this->merchantId, $this->apiMerchantKey)
                ->get($apiUrl, $params)
                ->json();
  }

  public function cancelSubscription(int $userId, int $subscriptionId, array $payload): array
  {
    $apiUrl = $this->apiBaseUrl . "projects/" . $this->projectId . "/users/" . $userId . "/subscriptions/" . $subscriptionId;

    return Http::withBasicAuth($this->merchantId, $this->apiMerchantKey)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->put($apiUrl, $payload)
                ->json();
  }

  public function getSubscriptionByUserId(array $params): array
  {
    $apiUrl = $this->apiBaseUrl . "merchants/" . $this->merchantId . "/subscriptions";

    return Http::withBasicAuth($this->merchantId, $this->apiMerchantKey)
                ->get($apiUrl, $params)
                ->json();
  }

}