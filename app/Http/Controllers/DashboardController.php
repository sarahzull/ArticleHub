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
        $user = auth()->user();
        $articles = [];

        if ($user && $user->subscription === null) {
            $currentPlan = 'Free';
        } else {
            $currentPlan = $user->subscription->plan->name;
        }

        $query = Article::with('author', 'category')->latest();

        if ($currentPlan === 'Basic') {
            $query->where('is_featured', true);
        } elseif ($currentPlan === 'Premium' || $currentPlan === 'Pro') {
            $articles = $query->limit(20)->get();
        }
    
        if ($currentPlan === 'Basic') {
            $articles = $query->limit(10)->get();
        }

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
