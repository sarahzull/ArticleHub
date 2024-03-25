<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Services\XsollaService;
use Illuminate\Console\Command;
use App\Models\SubscriptionUser;

class UpdateSubscriptionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update subscription status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subscriptions = SubscriptionUser::where('status', 'active')
            ->get();

        foreach ($subscriptions as $subscription) {
            $response = XsollaService::getSubscriptionByUserId($subscription->user_id);
            
            if ($response == []) {
                $subscription->update([
                    'status' => 'canceled'
                ]);
            } else {
                $lastChargeDate = Carbon::parse($response[0]['date_last_charge']);
                $endDate = $lastChargeDate->addDays(30);

                $subscription->update([
                    'status' => $response[0]['status'],
                    'updated_at' => now(),
                    'end_date' => $endDate
                ]);
            }
        }

        $this->info('Subscription status updated successfully');
    }
}
