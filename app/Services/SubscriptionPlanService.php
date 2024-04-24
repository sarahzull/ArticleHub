<?php

namespace App\Services;

use App\Models\SubscriptionPlan;

/**
 * Class SubscriptionPlanService
 * @package App\Services
 */
class SubscriptionPlanService
{
  //get subscription plan
  public function getSubscriptionPlan($plan_id)
  {
    return SubscriptionPlan::with('permission')
        ->where('plan_id', $plan_id)
        ->first();
  }
}
