<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Services\XsollaService;
use App\Models\SubscriptionUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request, XsollaService $xsollaService): Response
    {
        $user = auth()->user()->load('subscription.plan');
        
        if ($user->subscription) {
            $userPlan = $user->subscription;
        } else {
            $userPlan = null;
        }

        $plans = $xsollaService->getPlans(10);

        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'userPlan' => $userPlan,
            'plans' => $plans,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updatePlan (Request $request) 
    {
        //
    }

    public function cancelPlan (Request $request, XsollaService $xsollaService, SubscriptionService $subscriptionService) 
    {
        $user = auth()->user();
        $activeSubscription = $subscriptionService->getActiveSubscriptionUser($user);
        Log::info("activeSubscription", ['activeSubscription' => $activeSubscription]);

        $response = $xsollaService->cancelSubscription($user->id, (int) $activeSubscription->subscription_id, 'canceled');

        if ($response['status'] === 'canceled' || $response['status'] === 'non_renewing') {
            $activeSubscription->update([
                'status' => $response['status'],
                'end_date' => now(),
            ]);
            
            return Redirect::route('profile.edit')->with('success', 'Subscription has been canceled.');
        } else {
            return Redirect::route('profile.edit')->with('error', 'Request failed.');
        }
    }

    public function nonRenewPlan (Request $request, XsollaService $xsollaService) 
    {
        $user_id = auth()->user()->id;
        $activeSubscription = SubscriptionUser::where('user_id', $user_id)
        ->where('status', 'active')
        ->first();

        $response = $xsollaService->cancelSubscription($user_id, (int) $activeSubscription->subscription_id, 'non_renewing');

        if ($response['status'] === 'canceled' || $response['status'] === 'non_renewing') {
            $activeSubscription->update([
                'status' => $response['status'],
                'end_date' => now(),
            ]);
            
            return Redirect::route('profile.edit')->with('success', 'Subscription has been canceled.');
        } else {
            return Redirect::route('profile.edit')->with('error', 'Request failed.');
        }
    }
}
