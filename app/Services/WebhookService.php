<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionUser;
use Illuminate\Support\Facades\Log;

/**
 * Class WebhookService
 * @package App\Services
 */
class WebhookService
{
    public static function userValidation ($request) 
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

    public static function createdSubscription ($request) 
    {
        Log::info("request - createdSubscription", ['request' => $request]);
        $user = $request['user'];
        $subscription = $request['subscription'];
        $type = $request['notification_type'];
        $subscriptionPlan = SubscriptionPlan::where('external_id', $subscription['plan_id'])->first();
        $newUser = SubscriptionUser::where('user_id', $user['id'])->where('status', 'active')->first();
        Log::info("newUser", ['newUser' => $newUser, 'subscriptionPlan' => $subscriptionPlan]);

        $newUser->update([
            'subscription_id' => $subscription['subscription_id'],
            'start_date' => Carbon::parse($subscription['date_create']),
            'end_date' => Carbon::parse($subscription['date_next_charge']),
        ]);

        $user = User::find($user['id']);
        $user->givePermissionTo($subscriptionPlan->permission->name);
    }

    public static function updatedSubscription($request)
    {
        Log::info("request - updatedSubscription", ['request' => $request]);
        $user = $request['user'];
        $subscription = $request['subscription'];

        SubscriptionUser::where('subscription_id', $subscription['subscription_id'])->update([
            'subscription_id' => $subscription['subscription_id'],
            'end_date' => Carbon::parse($subscription['date_next_charge']),
            'status' => 'active',
            'updated_at' => now(),
        ]);
    }

    public static function canceledSubscription ($request)
    {
        Log::info("request - cancelSubscription", ['request' => $request]);
        $user = $request['user'];
        $subscription = $request['subscription'];
        $plan = SubscriptionPlan::with('permission')->where('external_id', $subscription['plan_id'])->first();
        
        $user = User::find($user['id']);
        $user->revokePermissionTo($plan->permission->name);

        $user = SubscriptionUser::where('user_id', $user['id'])
            ->where('subscription_id', $subscription['subscription_id'])
            ->where('status', 'active')
            ->update([
            'status' => 'canceled',
            'end_date' => Carbon::parse($subscription['date_next_charge']),
            'updated_at' => now(),
        ]);
    }

    public static function nonRenewalSubscription ($request)
    {
        Log::info("request - nonRenewalSubscription", ['request' => $request]);
        $user = $request['user'];
        $subscription = $request['subscription'];
        $plan = SubscriptionPlan::with('permission')->where('external_id', $subscription['plan_id'])->first();
        
        $user = User::find($user['id']);
        $user->revokePermissionTo($plan->permission->name);

        $user = SubscriptionUser::where('user_id', $user['id'])
            ->where('subscription_id', $subscription['subscription_id'])
            ->where('status', 'non_renewing')
            ->update([
            'status' => 'non_renewing',
            'end_date' => Carbon::parse($subscription['date_end']),
            'updated_at' => now(),
        ]);
    }

    public static function payment ($request) 
    {
        Log::info("request - payment", ['request' => $request]);
        // $user = $request->input('user');
        // $subscription = $request->input('subscription');

        // SubscriptionUser::where('user_id', $user['id'])->update([
        //     'subscription_id' => $subscription['subscription_id'],
        //     'status' => 'active',
        // ]);
    }
}
