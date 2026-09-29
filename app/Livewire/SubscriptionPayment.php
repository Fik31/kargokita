<?php

namespace App\Livewire;

use App\Models\Referral;
use App\Models\Subscription;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SubscriptionPayment extends Component
{
    public $hasActiveSubscription = false;

    public function mount()
    {
        $this->hasActiveSubscription = Auth::user()->is_subscribed;
    }

    public function processPayment()
    {
        if ($this->hasActiveSubscription) {
            session()->flash('error', 'Anda sudah memiliki langganan aktif.');
            return;
        }

        DB::beginTransaction();

        try {
            $user = Auth::user();

            // 1. Create Subscription
            Subscription::create([
                'user_id' => $user->id,
                'amount' => 1000000,
                'status' => 'active',
                'started_at' => now(),
                'expires_at' => now()->addYear(),
            ]);

            // 2. Update User
            $user->update(['is_subscribed' => true]);

            // 3. Process Pending Referrals (if this user was referred by someone)
            $pendingReferral = Referral::where('referred_id', $user->id)->where('status', 'pending')->first();
            if ($pendingReferral) {
                $pendingReferral->update(['status' => 'paid']);
                
                // Credit the referrer's wallet
                $referrerWallet = Wallet::firstOrCreate(['user_id' => $pendingReferral->referrer_id]);
                $referrerWallet->increment('balance', $pendingReferral->commission_amount);
                
                WalletTransaction::create([
                    'wallet_id' => $referrerWallet->id,
                    'type' => 'credit',
                    'amount' => $pendingReferral->commission_amount,
                    'description' => 'Komisi referral dari pendaftaran ' . $user->name,
                    'reference_type' => 'referral',
                    'reference_id' => $pendingReferral->id,
                ]);
            }

            DB::commit();

            $this->hasActiveSubscription = true;
            session()->flash('message', 'Pembayaran deposit berhasil! Anda sekarang adalah Member Resmi Cargo Ekosistem.');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat memproses pembayaran.');
        }
    }

    public function render()
    {
        return view('livewire.subscription-payment');
    }
}
