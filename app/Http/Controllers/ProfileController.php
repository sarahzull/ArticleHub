<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\XsollaService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = auth()->user()->load('subscription.plan');
        
        if ($user->subscription) {
            $userPlan = $user->subscription;
        } else {
            $userPlan = null;
        }

        $plans = XsollaService::getPlans(null);

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

    public function cancelPlan (Request $request) 
    {
        $user_id = auth()->user()->id;
        $subscription = XsollaService::getSubscriptionByUserId($user_id);
        $subscription_id = $subscription[0]['id'];

        $response = XsollaService::cancelSubscription($user_id, $subscription_id, 'canceled');

        if ($response['status'] == 'canceled') {
            return Redirect::route('profile.edit')->with('success', 'Subscription has been canceled.');
        } else {
            return Redirect::route('profile.edit')->with('error', 'Request failed.');
        }
    }
}
