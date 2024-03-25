<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\XsollaService;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionUser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

class SubscriptionController extends Controller
{
    public function index () 
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

        return Inertia::render('SubscriptionPlan/Index', [
            'plans' => $response->json()
        ]);
    }

    public function generateToken (Request $request)
    {
        // https://api.xsolla.com/merchant/v2/merchants/{merchant_id}/token
        $merchantId = Config::get('services.xsolla.merchant_id');
        $projectId = Config::get('services.xsolla.project_id');
        $apiKey = Config::get('services.xsolla.api_key');
        $url = Config::get('services.xsolla.api_url') . "merchants/" . $merchantId . "/token";

        $plan_external_id = $request->input('external_id');
        $plan_id = $request->input('plan_id');
        $user_id = $request->input('user_id');
        // $external_id = Str::random();

        $user = User::find($user_id);

        $payload = [
            "user" => [
                "id" => ["value" => $user->id, "hidden" => true],
                "email" => ["value" => $user->email],
                "name" => ["value" => $user->name, "hidden" => false],
            ],
            "settings" => [
                "project_id" => (int) $projectId,
                "payment_method" => 1380,
                "currency" => "MYR",
            ],
            "purchase" => ["subscription" => ["plan_id" => $plan_id]],
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

    public function redirect (Request $request)
    {
        // https://subscriptions.xsolla.com​/api/user/v1/projects/{project_id}/subscriptions/buy
        $merchantId = Config::get('services.xsolla.merchant_id');
        $projectId = Config::get('services.xsolla.project_id');
        $apiKey = Config::get('services.xsolla.api_key');
        $url = Config::get('services.xsolla.api_subs_url') . "user/v1/projects/" . $projectId . "/subscriptions/buy";
        
        // $plan_external_id = $request->input('external_id');
        // $external_id = Str::random();
        $plan_id = $request->input('plan_id');

        $user = User::find(auth()->user()->id);
        $plan = SubscriptionPlan::where('plan_id', $plan_id)->first();
        $token = XsollaService::createUserToken($user, $plan);

        SubscriptionUser::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'status' => 'new',
        ]);

        $redirectUrl = "https://sandbox-secure.xsolla.com/paystation4/?token=".$token['token'];
        
        return Redirect::route('redirect', ['redirectUrl' => $redirectUrl]);
    }

    public function callback (Request $request)
    {   
        $subs = SubscriptionUser::where('user_id', $request->input('user_id'))
                                ->where('status', 'new')
                                ->first();
                                
        $subs->update([
            'status' => 'active',
            'invoice_id' => $request->input('invoice_id'),
        ]);

        return redirect()->route('dashboard', ['status' => $subs->status]);
    }
}
