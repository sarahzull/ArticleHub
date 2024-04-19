<?php

namespace App\Services;

use Carbon\Carbon;
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
        Log::info("request - userValidation", $request->all());
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
        Log::info("request - createdSubscription", $request->all());
        $user = $request->input('user');
        $subscription = $request->input('subscription');
        $subscriptionPlan = SubscriptionPlan::where('plan_id', $subscription['plan_id'])->first();

        SubscriptionUser::create([
            'user_id' => $user['id'],
            'subscription_plan_id' => $subscriptionPlan->id,
            'subscription_id' => $subscription['subscription_id'],
            'start_date' => $subscription['date_create'],
            'end_date' => Carbon::parse(subscription['date_create'])->addDays(30),
            'status' => 'new',
        ]);
    }

    public static function updatedSubscription ($request) 
    {
        Log::info("request - updatedSubscription", $request->all());
        $user = $request->input('user');
        $subscription = $request->input('subscription');

        SubscriptionUser::where('subscription_plan_id', $subscription['subscription_id'])->update([
            // 'subscription_id' => $subscription['subscription_id'],
            'status' => 'active',
        ]);
    }

    public static function payment ($request) 
    {
        Log::info("request - payment", $request->all());
        // $user = $request->input('user');
        // $subscription = $request->input('subscription');

        // SubscriptionUser::where('user_id', $user['id'])->update([
        //     'subscription_id' => $subscription['subscription_id'],
        //     'status' => 'active',
        // ]);
    }
}
