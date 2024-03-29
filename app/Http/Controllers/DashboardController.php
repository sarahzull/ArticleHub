<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;

class DashboardController extends Controller
{
    public function index (Request $request) 
    {
        $articles = Article::with('author', 'category')
            ->latest()
            ->limit(10)
            ->get();

        $user = auth()->user();

        if ($user) {
            if ($user->subscription) {
                $userWithSubscription = $user->load('subscription.plan');
                $currentPlan = optional($userWithSubscription->subscription->plan)->name ?? 'Free';
            } else {
                $currentPlan = 'Free';
            }
        } else {
            $currentPlan = 'Free';
        }

        if ($request->filled('status') && $request->status == 'active') {
            session()->flash('success', 'Your subscription has been activated!');
        }

        session()->forget('success');

        return Inertia::render('Dashboard', [
            'articles' => $articles,
            'currentPlan' => $currentPlan
        ]);
    }

    public function dashboardRedirect () 
    {
        return redirect()->route('dashboard');
    }

    public function redirect (Request $request) 
    {
        return Inertia::render('Redirect', [
            'redirectUrl' => $request->input('redirectUrl')
        ]);
    }
}
