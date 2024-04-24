<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\XsollaService;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionUser;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

class SubscriptionController extends Controller
{
    public function index (XsollaService $xsollaService) 
    {
        $plans = $xsollaService->getPlans();

        return Inertia::render('SubscriptionPlan/Index', [
            'plans' => $plans
        ]);
    }

    public function redirect (Request $request, XsollaService $xsollaService, SubscriptionService $subscriptionService)
    {
        $plan_id = $request->input('plan_id');
        $user = auth()->user();
        $items = [];

        // find active subscription user
        $activeSubscription = $subscriptionService->getActiveSubscriptionUser($user);

        // retrieve selected subscription plan
        $plan = $subscriptionService->getSubscriptionPlan($plan_id);


        if ($activeSubscription) {
            $items = [
                "change_plan" => true,
            ];
            $user->revokePermissionTo($plan->permission->name);
    
            // cancel current active subscription
            $updateSubscription = [
                'status' => 'canceled',
                'end_date' => now(),
            ];
            $subscriptionService->updateSubscription($activeSubscription, $updateSubscription);
        }

        // create new subscription for user
        $subscriptionData = [
            'start_date' => now(),
            'status' => 'new',
        ];
        $newSubscription = $subscriptionService->createSubscription($user, $plan, $subscriptionData);
        
        $token = $xsollaService->createUserToken($user, $plan, $items, $newSubscription->id);
        Log::info("token", ["token" => $token]);
        $redirectUrl = $xsollaService->getRedirectUrl($token);
        Log::info("redirectUrl", ["redirectUrl" => $redirectUrl]);
        
        return Redirect::route('redirect', ['redirectUrl' => $redirectUrl]);
    }

    public function callback (Request $request)
    {   
        $subs = SubscriptionUser::where('id', $request->input('user_sub_id'))
        ->where('status', 'new')
        ->first();
        Log::info("callback received", $request->all());
        
        $user = User::find($request->input('user_id'));
        $plan = SubscriptionPlan::with('permission')->where('id', $subs->subscription_plan_id)->first();

        if ($request->input('status') == 'done') {
            $subs->update([
                'status' => 'active',
                'invoice_id' => $request->input('invoice_id'),
            ]);
            $user->givePermissionTo($plan->permission->name);
        }

        return redirect()->route('dashboard', ['status' => $subs->status]);
    }
}
