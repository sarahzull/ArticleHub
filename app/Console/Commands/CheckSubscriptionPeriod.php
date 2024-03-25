<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubscriptionUser;
use Carbon\Carbon;

class CheckSubscriptionPeriod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check-period';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check subscription period and update status if needed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subscriptions = SubscriptionUser::where('status', 'non_renewing')
            ->get();

        foreach ($subscriptions as $subscription) {
            if (Carbon::parse($subscription->end_date)->isPast()) {
                $subscription->update([
                    'status' => 'ended'
                ]);
            }
        }

        $this->info('Subscription period has been checked successfully.');
    }
}
