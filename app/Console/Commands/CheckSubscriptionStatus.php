<?php

namespace App\Console\Commands;

use App\Models\Load;
use App\Models\Subscription;
use App\Models\Trip;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckSubscriptionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check expired subscriptions and process refunds or claims based on trip activity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredSubscriptions = Subscription::where('status', 'active')
            ->where('expires_at', '<=', now())
            ->get();

        $this->info("Found {$expiredSubscriptions->count()} expired subscriptions.");

        foreach ($expiredSubscriptions as $sub) {
            DB::transaction(function () use ($sub) {
                $user = $sub->user;
                $hasActivity = false;

                if ($user->hasRole('driver')) {
                    // Check if driver had any trips during the subscription period
                    $hasActivity = Trip::where('driver_id', $user->id)
                        ->where('created_at', '>=', $sub->started_at)
                        ->where('created_at', '<=', $sub->expires_at)
                        ->exists();
                } elseif ($user->hasRole('merchant')) {
                    // Check if merchant had any loads that got a driver (in_transit or completed status)
                    $hasActivity = Load::where('merchant_id', $user->id)
                        ->whereIn('status', ['in_transit', 'completed'])
                        ->where('created_at', '>=', $sub->started_at)
                        ->where('created_at', '<=', $sub->expires_at)
                        ->exists();
                }

                if ($hasActivity) {
                    $sub->update(['status' => 'claimed_by_admin']);
                    $this->info("Subscription #{$sub->id} claimed by admin (User had activity).");
                } else {
                    $sub->update(['status' => 'refunded']);
                    $this->info("Subscription #{$sub->id} refunded (User had no activity).");
                    // Implement actual refund logic here if needed (e.g., payment gateway API)
                }

                // Revoke subscription status
                $user->update(['is_subscribed' => false]);
            });
        }

        $this->info('Subscription check completed.');
    }
}
