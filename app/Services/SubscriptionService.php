<?php

namespace App\Services;

use App\Enums\SubscriptionStatus;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionUser;

/**
 * Class SubscriptionService
 * @package App\Services
 */
class SubscriptionService
{
    //get active subscription
    public function getActiveSubscriptionUser($user_id)
    {
      return SubscriptionUser::where('user_id', $user_id)
          ->where('status', SubscriptionStatus::Active())
          ->first();
    }

    //get new subscription
    public function getNewSubscriptionUser($user_id)
    {
      return SubscriptionUser::where('user_id', $user_id)
          ->where('status', SubscriptionStatus::New())
          ->first();
    }

    //get non renew subscription
    public function getNonRenewSubscriptionUser($user_id)
    {
      return SubscriptionUser::where('user_id', $user_id)
          ->where('status', SubscriptionStatus::NonRenewing())
          ->first();
    }

    //get by id
    public function getSubscriptionUserById($id)
    {
      return SubscriptionUser::find($id);
    }

    // create new subscription for user
    public function createSubscription($user, $plan, $items)
    {
      return SubscriptionUser::create([
          'user_id' => $user->id,
          'subscription_plan_id' => $plan->id,
          'status' => $items['status'] ?? SubscriptionStatus::New(),
          'invoice_id' => $items['invoice_id'] ?? null,
      ]);
    }
    
    //update subscription user
    public function updateSubscription($subscriptionUser, $items, $plan = null)
    {
        $subscriptionUser->update([
            'status' => $items['status'],
            'invoice_id' => $items['invoice_id'] ?? null,
        ]);

        if ($plan) {
          $subscriptionUser->givePermissionTo($plan->permission->name);
        }

        return $subscriptionUser;
    }
}
