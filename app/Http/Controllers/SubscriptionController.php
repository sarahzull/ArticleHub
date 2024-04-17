<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\XsollaService;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionUser;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

class SubscriptionController extends Controller
{
    public function index (XsollaService $xsollaService) 
    {
        $plans = $xsollaService->getPlans(10);

        return Inertia::render('SubscriptionPlan/Index', [
            'plans' => $plans
        ]);
    }

    public function redirect (Request $request, XsollaService $xsollaService)
    {
        $plan_id = $request->input('plan_id');
        $user_id = auth()->user()->id;
        $items = [];

        $userSubcription = SubscriptionUser::where('user_id', auth()->user()->id)->where('status', 'active')->first();
        $user = User::find($user_id);
        $plan = SubscriptionPlan::with('permission')->where('plan_id', $plan_id)->first();

        if ($userSubcription) {
            $items = [
                "change_plan" => true,
            ];

            $user->revokePermissionTo($plan->permission->name);
            SubscriptionUser::where('user_id', $user_id)->update(['status' => 'canceled']);
        } 

        $userSub = SubscriptionUser::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'status' => 'new',
        ]);
        
        $token = $xsollaService->createUserToken($user, $plan, $items, $userSubId = $userSub->id);

        $redirectUrl = "https://sandbox-secure.xsolla.com/paystation4/?token=".$token['token'];
        
        return Redirect::route('redirect', ['redirectUrl' => $redirectUrl]);
    }

    public function callback (Request $request)
    {   
        $subs = SubscriptionUser::where('id', $request->input('user_sub_id'))
                                ->where('status', 'new')
                                ->first();
        Log::info("callback", $request->all());
        Log::info("subs", $subs);
        $user = User::find($request->input('user_id'));
        $plan = SubscriptionPlan::with('permission')->where('id', $subs->subscription_plan_id)->first();

        if ($request->input('status') == 'done') {
            $subs->update([
                'status' => 'active',
                'invoice_id' => $request->input('invoice_id'),
            ]);
            $user->givePermissionTo($plan->permission->name);
        }

        return redirect()->route('dashboard', ['status' => $subs->status]);
    }
}
