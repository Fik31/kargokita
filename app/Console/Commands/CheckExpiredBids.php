<?php

namespace App\Console\Commands;

use App\Models\Load;
use App\Notifications\BidExpiredNotification;
use Illuminate\Console\Command;

class CheckExpiredBids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bids:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired load bids and send notifications to merchants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredLoads = Load::where('status', 'open')
            ->whereNotNull('bid_deadline')
            ->where('bid_deadline', '<=', now())
            ->get();

        foreach ($expiredLoads as $load) {
            // Check if there are any accepted or pending bids
            $activeBidsCount = $load->bids()->whereIn('status', ['pending', 'accepted'])->count();

            if ($activeBidsCount == 0) {
                // No driver bid. Check if there are rejections.
                $rejectedBidsCount = $load->bids()->where('status', 'rejected')->count();

                if ($rejectedBidsCount > 0) {
                    // Case 2: All drivers who interacted rejected it
                    $avgPrice = $load->bids()->where('status', 'rejected')->avg('suggested_price');
                    $message = "Bid untuk muatan '{$load->title}' telah berakhir dan ditolak oleh {$rejectedBidsCount} driver. Rata-rata saran harga dari driver adalah Rp ".number_format($avgPrice, 0, ',', '.').'. Silakan buat bid baru dengan penyesuaian harga.';
                } else {
                    // Case 1: Nobody interacted
                    $message = "Bid untuk muatan '{$load->title}' telah berakhir dan tidak ada driver yang merespon. Saran: Coba naikkan harga, turunkan berat muatan, atau perpendek jarak.";
                }

                // Send notification to merchant
                if ($load->merchant) {
                    $load->merchant->notify(new BidExpiredNotification($load, $message));
                }

                // Close the load
                $load->update(['status' => 'closed']);

                $this->info("Load {$load->id} closed and notification sent to merchant.");
            } else {
                // Drivers have bid, leave it open for merchant to choose or close it manually?
                // The prompt didn't specify closing it if there are bids, usually it stays open for merchant decision.
                $this->info("Load {$load->id} expired but has active bids. Left for merchant to decide.");
            }
        }

        $this->info('Checked all expired bids.');
    }
}
