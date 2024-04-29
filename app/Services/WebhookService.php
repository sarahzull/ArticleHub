<?php

namespace App\Services;

use App\Enums\SubscriptionStatus;
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

    public function __construct(SubscriptionPlanService $subscriptionPlanService, SubscriptionService $subscriptionService) 
    {
        $this->subscriptionPlanService = $subscriptionPlanService;
        $this->subscriptionService = $subscriptionService;
    }

    public function userValidation ($request) 
    {
        Log::info("request - userValidation", ['request' => $request]);
        $userData = $request->input('user');

        if (isset($userData['id'])) {
            $exist = UserService::checkUserExists($userData['id']);

            if ($exist) {
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
        }

        return response()->json([
            'error' => [
                'code' => "INVALID_PARAMETER",
                'message' => 'Invalid parameter'
            ]
        ], 400);
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

        SubscriptionUser::where('subscription_id', $subscription['subscription_id'])->update([
            'subscription_id' => $subscription['subscription_id'],
            'end_date' => Carbon::parse($subscription['date_next_charge']),
            'status' => SubscriptionStatus::Active(),
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
            ->where('status', SubscriptionStatus::Active())
            ->update([
            'status' => SubscriptionStatus::Canceled(),
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
            ->where('status', SubscriptionStatus::NonRenewing())
            ->update([
            'status' => SubscriptionStatus::NonRenewing(),
            'end_date' => Carbon::parse($subscription['date_next_charge']),
            'updated_at' => now(),
        ]);
    }

    public function payment ($request) 
    {
        Log::info("request - payment", ['request' => $request]);
    }
}
