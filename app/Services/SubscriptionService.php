<?php

namespace App\Services;

use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionUser;
use App\Enums\SubscriptionStatus;

/**
 * Class SubscriptionService
 * @package App\Services
 */
class SubscriptionService
{
    /**
     * Get the active subscription for a user.
     *
     * @param int $user_id The ID of the user.
     * @return SubscriptionUser|null The active subscription user, or null if not found.
     */
    public function getActiveSubscriptionUser(int $user_id): ?SubscriptionUser
    {
      return SubscriptionUser::where('user_id', $user_id)
          ->where('status', SubscriptionStatus::Active())
          ->first();
    }

    /**
     * Get the new subscription for a user.
     *
     * @param int $user_id The ID of the user.
     * @return SubscriptionUser|null The new subscription user, or null if not found.
     */
    public function getNewSubscriptionUser(int $user_id): ?SubscriptionUser
    {
      return SubscriptionUser::where('user_id', $user_id)
          ->where('status', SubscriptionStatus::New())
          ->first();
    }

    /**
     * Get the non-renewing subscription for a user.
     *
     * @param int $user_id The ID of the user.
     * @return SubscriptionUser|null The non-renewing subscription user, or null if not found.
     */
    public function getNonRenewSubscriptionUser(int $user_id): ?SubscriptionUser
    {
      return SubscriptionUser::where('user_id', $user_id)
          ->where('status', SubscriptionStatus::NonRenewing())
          ->first();
    }

    /**
     * Get a subscription user by ID.
     *
     * @param int $id The ID of the subscription user.
     * @return SubscriptionUser|null The subscription user, or null if not found.
     */
    public function getSubscriptionUserById(int $id): ?SubscriptionUser
    {
      return SubscriptionUser::find($id);
    }

    /**
     * Create a new subscription for a user.
     *
     * @param User $user The user for whom the subscription is created.
     * @param SubscriptionPlan $plan The subscription plan.
     * @param string|null $status The status of the subscription (default: 'New').
     * @param int|null $invoice The invoice ID associated with the subscription (optional).
     * @return SubscriptionUser The newly created subscription user.
     */
    public function createSubscription(User $user, SubscriptionPlan $plan, $status = null, int $invoice = null): SubscriptionUser
    {
      return SubscriptionUser::create([
          'user_id' => $user->id,
          'subscription_plan_id' => $plan->id,
          'status' => $status ?? SubscriptionStatus::New(),
          'invoice_id' => $invoice,
      ]);
    }
    
    /**
     * Update a subscription user's details.
     *
     * @param SubscriptionUser $subscriptionUser The subscription user to update.
     * @param string|null $status The status of the subscription (default: 'New').
     * @param int|null $invoice The invoice ID associated with the subscription (optional).
     * @param SubscriptionPlan|null $plan The new subscription plan (optional).
     * @return SubscriptionUser The updated subscription user.
     */
    public function updateSubscription(SubscriptionUser $subscriptionUser, $status = null, int $invoice = null, ?SubscriptionPlan $plan = null): SubscriptionUser
    {
        $subscriptionUser->update([
            'status' => $status,
            'invoice_id' => $invoice,
        ]);

        if ($plan) {
          $subscriptionUser->givePermissionTo($plan->permission->name);
        }

        return $subscriptionUser;
    }
}
