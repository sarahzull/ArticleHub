<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        
        if ($user) {
            if ($user->subscription) {
                $userWithSubscription = $user->load('subscription.plan');
                $currentPlan = optional($userWithSubscription->subscription->plan)->name ?? 'Free';
            } else {
                $currentPlan = 'Free';
            }
            
            if ($user->getDirectPermissions()) {
                $basicPlan = $user->hasDirectPermission('basic plan');
                $premiumPlan = $user->hasDirectPermission('premium plan');
                $proPlan = $user->hasDirectPermission('pro plan');
            }
        } else {
            $currentPlan = 'Free';
            $basicPlan = false;
            $premiumPlan = false;
            $proPlan = false;
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
                'currentPlan' => $currentPlan,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'can' => [
                'basic' => $basicPlan,
                'premiun' => $premiumPlan,
                'pro' => $proPlan,
            ]
        ];
    }
}
