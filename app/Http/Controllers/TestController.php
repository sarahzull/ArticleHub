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
    public function getSubscriptions ()
    {
        $merchantId = Config::get('services.xsolla.merchant_id');
        $merchantApiKey = Config::get('services.xsolla.merchant_api_key');
        $url = Config::get('services.xsolla.api_url') . "merchants/" . $merchantId . "/subscriptions";

        $params = [
            'limit' => 10,
        ];

        $response = Http::withBasicAuth($merchantId, $merchantApiKey)
        ->withHeaders(['Content-Type' => 'application/json'])
        ->get($url, $params);

        if ($response->successful()) {
            $data = $response->json();
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Request failed.'], $response->status());
        }

    }
}
