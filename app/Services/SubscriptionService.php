<?php

namespace App\Services;

use App\Models\SubscriptionPlan;
use App\Models\SubscriptionUser;

/**
 * Class SubscriptionService
 * @package App\Services
 */
class SubscriptionService
{
    //get active subscription
    public function getActiveSubscriptionUser($user)
    {
      return SubscriptionUser::where('user_id', $user->id)
          ->where('status', 'active')
          ->first();
    }

    //get new subscription
    public function getNewSubscriptionUser($user)
    {
      return SubscriptionUser::where('user_id', $user->id)
          ->where('status', 'new')
          ->first();
    }

    //get non renew subscription
    public function getNonRenewSubscriptionUser($user)
    {
      return SubscriptionUser::where('user_id', $user->id)
          ->where('status', 'non_renewing')
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
          'start_date' => $items['start_date'], //can get from webhook
          'end_date' => $items['start_date']->addDays(30), //can get from webhook
          'status' => $items['status'] ?? 'new',
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
