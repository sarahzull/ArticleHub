<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionUser;
use Illuminate\Support\Facades\Log;
use App\Services\SubscriptionService;
use App\Services\SubscriptionPlanService;

/**
 * Class WebhookService
 * @package App\Services
 */
class WebhookService
{
    private ?SubscriptionPlanService $subscriptionPlanService;
    private ?SubscriptionService $subscriptionService;
    private ?UserService $userService;

    public function __construct(SubscriptionPlanService $subscriptionPlanService, SubscriptionService $subscriptionService, UserService $userService) 
    {
        $this->subscriptionPlanService = $subscriptionPlanService;
        $this->subscriptionService = $subscriptionService;
        $this->userService = $userService;
    }

    public function userValidation ($request) 
    {
        $userData = $request['user'];

        if (isset($userData['id'])) {
            $userExists = $this->userService->checkUserExists($userData['id']);
    
            if ($userExists) {
                return response()->json([
                    'code' => "VALID_USER",
                    'message' => 'Valid user'
                ], 200);
            } else {
                return response()->json([
                    'error' => [
                        'code' => "INVALID_USER",
                        'message' => 'Invalid user'
                    ]
                ], 400);
            }
        } else {
            return response()->json([
                'error' => [
                    'code' => "INVALID_PARAMETER",
                    'message' => 'Invalid parameter'
                ]
            ], 400);
        }
    }

    public function createdSubscription ($request) 
    {
        Log::info("request - createdSubscription", ['request' => $request]);

        $user = $request['user'];
        $subscription = $request['subscription'];

        $subscriptionPlan = $this->subscriptionPlanService->getByExternalId($subscription['plan_id']);
        $activeUser = $this->subscriptionService->getActiveSubscriptionUser($user['id']) ?? $this->subscriptionService->getNewSubscriptionUser($user['id']);

        Log::info("activeUser", ['activeUser' => $activeUser, 'subscriptionPlan' => $subscriptionPlan]);

        $activeUser->update([
            'subscription_id' => $subscription['subscription_id'],
            'start_date' => Carbon::parse($subscription['date_create']),
            'end_date' => Carbon::parse($subscription['date_next_charge']),
        ]);

        $user = User::find($user['id']);
        $user->givePermissionTo($subscriptionPlan->permission->name);
    }

    public function updatedSubscription($request)
    {
        Log::info("request - updatedSubscription", ['request' => $request]);
        $user = $request['user'];
        $subscription = $request['subscription'];
        Log::info("subscription - updatedSubscription", ['subscription' => $subscription]);

        SubscriptionUser::where('subscription_id', $subscription['subscription_id'])->update([
            'subscription_id' => $subscription['subscription_id'],
            'end_date' => Carbon::parse($subscription['date_next_charge']),
            'status' => SubscriptionService::ACTIVE,
            'updated_at' => now(),
        ]);
    }

    public function canceledSubscription ($request)
    {
        Log::info("request - cancelSubscription", ['request' => $request]);
        $user = $request['user'];
        $subscription = $request['subscription'];
        $plan = SubscriptionPlan::with('permission')->where('external_id', $subscription['plan_id'])->first();
        
        $user = User::find($user['id']);
        $user->revokePermissionTo($plan->permission->name);

        $user = SubscriptionUser::where('user_id', $user['id'])
            ->where('subscription_id', $subscription['subscription_id'])
            ->where('status', SubscriptionService::ACTIVE)
            ->update([
            'status' => SubscriptionService::ACTIVE,
            'end_date' => Carbon::parse($subscription['date_next_charge']),
            'updated_at' => now(),
        ]);
    }

    public function nonRenewalSubscription ($request)
    {
        Log::info("request - nonRenewalSubscription", ['request' => $request]);
        $user = $request['user'];
        $subscription = $request['subscription'];
        $plan = SubscriptionPlan::with('permission')->where('external_id', $subscription['plan_id'])->first();
        
        $user = User::find($user['id']);
        $user->revokePermissionTo($plan->permission->name);

        $user = SubscriptionUser::where('user_id', $user['id'])
            ->where('subscription_id', $subscription['subscription_id'])
            ->where('status', SubscriptionService::NON_RENEWAL)
            ->update([
            'status' => SubscriptionService::NON_RENEWAL,
            'end_date' => Carbon::parse($subscription['date_next_charge']),
            'updated_at' => now(),
        ]);
    }

    public function payment ($request) 
    {
        Log::info("request - payment", ['request' => $request]);
    }
}
