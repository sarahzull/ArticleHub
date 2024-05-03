<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\XsollaService;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionUser;
use App\Http\Requests\CallbackRequest;
use App\Http\Requests\RedirectRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Services\SubscriptionPlanService;

class SubscriptionController extends Controller
{
    public function index (XsollaService $xsollaService) 
    {
        $plans = $xsollaService->getPlans();

        return Inertia::render('SubscriptionPlan/Index', [
            'plans' => $plans
        ]);
    }

    public function redirect (RedirectRequest $request, XsollaService $xsollaService, SubscriptionService $subscriptionService, SubscriptionPlanService $subscriptionPlanService)
    {
        $planId = $request->input('plan_id');
        $user = auth()->user();
        $items = [];
        $userSubscriptionId = null; 

        $activeSubscription = $subscriptionService->getActiveSubscriptionUser($user->id);
        $plan = $subscriptionPlanService->getSubscriptionPlan($planId);

        if ($activeSubscription) {
            $items = [
                "change_plan" => true,
            ];
            $user->revokePermissionTo($plan->permission->name);
            $userSubscriptionId = $activeSubscription->id;
    
            // // cancel current active subscription
            // $status = SubscriptionService::CANCELED;
            // $subscriptionService->updateSubscription($activeSubscription, $status);
        } else {
            $newSubscription = $subscriptionService->createSubscription($user, $plan, SubscriptionService::NEW);
            $userSubscriptionId = $newSubscription->id;
        }
        
        $token = $xsollaService->createUserToken($user, $plan, $items, $userSubscriptionId);
        $tokenData = $token['token'];
        $redirectUrl = $xsollaService->getRedirectUrl($tokenData);
        
        return Redirect::route('redirect', ['redirectUrl' => $redirectUrl]);
    }

    public function callback(CallbackRequest $request, SubscriptionService $subscriptionService, SubscriptionPlanService $subscriptionPlanService)
    {
        $userSub = $subscriptionService->getSubscriptionUserById($request->input('user_sub_id'));

        // Log::info("callback received", $request->all());
        // Log::info("userSub", ['userSub' => $userSub]);

        if ($request->input('status') == 'done') {
            $status = SubscriptionService::ACTIVE;
            $invoice_id = $request->input('invoice_id');

            $plan = $subscriptionPlanService->getSubscriptionPlan($userSub->subscription_plan_id);
            $subscriptionUser = $subscriptionService->updateSubscription($userSub, $status, $invoice_id, $plan);

            Log::info("update status after callback - ", ['subscriptionUser' => $subscriptionUser]);

            session()->flash('success', 'Subscription has been activated!');
        }

        return redirect()->route('dashboard', ['status' => $userSub->status]);
    }
}
