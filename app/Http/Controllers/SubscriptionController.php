<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\XsollaService;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionUser;
use App\Services\SubscriptionPlanService;
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

    public function redirect (Request $request, XsollaService $xsollaService, SubscriptionService $subscriptionService, SubscriptionPlan $subscriptionPlan)
    {
        $planId = $request->input('plan_id');
        $user = auth()->user();
        $items = [];

        // find active subscription user
        $activeSubscription = $subscriptionService->getActiveSubscriptionUser($user);

        // retrieve selected subscription plan
        $plan = $subscriptionPlan->getSubscriptionPlan($planId);


        if ($activeSubscription) {
            $items = [
                "change_plan" => true,
            ];
            $user->revokePermissionTo($plan->permission->name);
    
            // cancel current active subscription
            $newStatus = 'canceled';
            $subscriptionService->updateSubscription($activeSubscription, $newStatus);
        }

        // create new subscription for user
        $subscriptionData = [
            'start_date' => now(),
            'status' => 'new',
        ];
        $newSubscription = $subscriptionService->createSubscription($user, $plan, $subscriptionData);
        
        $token = $xsollaService->createUserToken($user, $plan, $items, $newSubscription->id);
        $tokenData = $token['token'];
        $redirectUrl = $xsollaService->getRedirectUrl($tokenData);
        
        return Redirect::route('redirect', ['redirectUrl' => $redirectUrl]);
    }

    public function callback (Request $request, SubscriptionService $subscriptionService, SubscriptionPlanService $subscriptionPlan, User $user)
    {   
        $userSub = $subscriptionService->getActiveSubscriptionUser($user);
        
        Log::info("callback received", $request->all());
        $plan = $subscriptionPlan->getSubscriptionPlan($userSub->subscription_plan_id);

        if ($request->input('status') == 'done') {
            $data = [
                'status' => 'active',
                'invoice_id' => $request->input('invoice_id'),
            ];
            $newSubscription = $subscriptionService->updateSubscription($userSub, $data);
            $newSubscription->givePermissionTo($plan->permission->name);
        }

        return redirect()->route('dashboard', ['status' => $userSub->status]);
    }
}
