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
     * Get subscription plan by ID.
     *
     * @param int $id
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
     * @param int $planId
     * @return SubscriptionPlan|null
     */
    public function getSubscriptionbyPlanId(string $planId): ?SubscriptionPlan
    {
        return SubscriptionPlan::with('permission')
            ->where('plan_id', $planId)
            ->first();
    }

    /**
     * Get subscription plan by external ID.
     *
     * @param string $externalId
     * @return SubscriptionPlan|null
     */
    public function getSubscriptionByExternalId(string $externalId): ?SubscriptionPlan
    {
        return SubscriptionPlan::with('permission')
            ->where('external_id', $externalId)
            ->first();
    }
}
