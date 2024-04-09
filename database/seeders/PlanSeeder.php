<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use App\Services\XsollaService;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'basic plan'],
            ['name' => 'premium plan'],
            ['name' => 'pro plan'],
        ]; 
        
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $plans = XsollaService::getPlans(10);
        $permissions = Permission::all()->keyBy(function ($item) {
            $firstWord = explode(' ', $item->name)[0];
            return ucfirst($firstWord);
        });

        foreach ($plans as $plan) {
            $planNameFirstWord = ucfirst(explode(' ', $plan['name']['en'])[0]);
            $permissionId = null;
            
            if (isset($permissions[$planNameFirstWord])) {
                $permissionId = $permissions[$planNameFirstWord]->id;
            }

            SubscriptionPlan::create([
                'plan_id' => $plan['id'],
                'name' => $plan['name']['en'],
                'description' => $plan['description']['en'],
                'price' => $plan['prices'][0]['amount'],
                'external_id' => $plan['external_id'],
                'permission_id' => $permissionId
            ]);
        }
    }
}
