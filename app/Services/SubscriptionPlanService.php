<?php

namespace App\Services;

use App\Models\SubscriptionPlan;

/**
 * Class SubscriptionPlanService
 * @package App\Services
 */
class SubscriptionPlanService
{
  /**
   * Get subscription plan by plan ID.
   *
   * @param int $plan_id
   * @return SubscriptionPlan|null
   */
  public function getSubscriptionPlan(int $plan_id): ?SubscriptionPlan
  {
      return SubscriptionPlan::with('permission')
          ->where('plan_id', $plan_id)
          ->first();
  }

  /**
   * Get subscription plan by external ID.
   *
   * @param string $external_id
   * @return SubscriptionPlan|null
   */
  public function getByExternalId(string $external_id): ?SubscriptionPlan
  {
      return SubscriptionPlan::where('external_id', $external_id)->first();
  }
}
