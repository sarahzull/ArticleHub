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
  
  // $merchantId = Config::get('services.xsolla.merchant_id');
  // $projectId = Config::get('services.xsolla.project_id');
  // $apiMerchantKey = Config::get('services.xsolla.api_key');

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

}