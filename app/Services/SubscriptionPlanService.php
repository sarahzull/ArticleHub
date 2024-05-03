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
  public function getSubscriptionbyId(int $id): ?SubscriptionPlan
  {
      return SubscriptionPlan::with('permission')
          ->where('id', $id)
          ->first();
  }

  /**
   * Get subscription plan by plan ID.
   *
   * @param int $plan_id
   * @return SubscriptionPlan|null
   */
  public function getSubscriptionbyPlanId(int $plan_id): ?SubscriptionPlan
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
  public function getSubscriptionByExternalId(string $external_id): ?SubscriptionPlan
  {
      return SubscriptionPlan::with('permission')
          ->where('external_id', $external_id)
          ->first();
  }
}
