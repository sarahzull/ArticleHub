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
        $newUser = SubscriptionUser::where('user_id', $user['id'])->where('status', 'new')->first();

        $newUser->update([
            'subscription_id' => $subscription['subscription_id'],
            'start_date' => Carbon::parse($subscription['date_create']),
            'end_date' => Carbon::parse($subscription['date_next_charge']),
        ]);

        if ($type === 'update_subscription') {
            $user = User::find($user['id']);
            $user->givePermissionTo($subscriptionPlan->permission->name);

            SubscriptionUser::where('subscription_id', $subscription['subscription_id'])->update([
                'status' => 'active',
            ]);
        }
    }

    public static function updatedSubscription ($request) 
    {
        Log::info("request - createdSubscription", ['request' => $request]);
        $user = $request['user'];
        $subscription = $request['subscription'];

        SubscriptionUser::where('subscription_id', $subscription['subscription_id'])->update([
            'status' => 'active',
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
