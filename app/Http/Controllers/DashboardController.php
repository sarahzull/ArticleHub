<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index () 
    {
        $articles = Article::with('author', 'category')
            ->latest()
            ->limit(5)
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

        return Inertia::render('Dashboard', [
            'articles' => $articles,
            'currentPlan' => $currentPlan
        ]);}

    public function redirect () 
    {
        return redirect()->route('dashboard');
    }
}
