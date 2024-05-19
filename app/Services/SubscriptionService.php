<?php

namespace App\Services;

use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionUser;
use Illuminate\Support\Facades\Log;

/**
 * Class SubscriptionService
 * @package App\Services
 */
class SubscriptionService
{
    /**
     * STATUSES
     */
    const NEW = 'new';
    const ACTIVE = 'active';
    const CANCELED = 'canceled';
    const NON_RENEWAL = 'non_renewing';

    /**
     * Get the active subscription for a user.
     *
     * @param int $userId The ID of the user.
     * @return SubscriptionUser|null The active subscription user, or null if not found.
     */
    public function getActiveSubscriptionUser(int $userId): ?SubscriptionUser
    {
      return SubscriptionUser::where('user_id', $userId)
          ->where('status', self::ACTIVE)
          ->with('plan.permission')
          ->first();
    }

    /**
     * Get the new subscription for a user.
     *
     * @param int $userId The ID of the user.
     * @return SubscriptionUser|null The new subscription user, or null if not found.
     */
    public function getNewSubscriptionUser(int $userId): ?SubscriptionUser
    {
      return SubscriptionUser::where('user_id', $userId)
          ->where('status', self::NEW)
          ->first();
    }

    /**
     * Get the non-renewing subscription for a user.
     *
     * @param int $userId The ID of the user.
     * @return SubscriptionUser|null The non-renewing subscription user, or null if not found.
     */
    public function getNonRenewSubscriptionUser(int $userId): ?SubscriptionUser
    {
      return SubscriptionUser::where('user_id', $userId)
          ->where('status', self::NON_RENEWAL)
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
          'status' => $status ?? self::NEW,
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
        $user = $subscriptionUser->user;
        $user->givePermissionTo($plan->permission->name);
      }

      return $subscriptionUser;
    }

    public function changeSubscription(User $user, SubscriptionPlan $plan)
    {
      // revoke existing permission if user has an active subscription
      $activeSubscription = $user->activeSubscription();
      if ($activeSubscription) {
          $user->revokePermissionTo($activeSubscription->plan->permission->name);
          $activeSubscription->cancel();
      }

      // create a new subscription for the user
      $newSubscription = self::createSubscription($user, $plan);

      return $newSubscription;
    }
}
