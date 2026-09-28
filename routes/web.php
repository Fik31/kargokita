<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\AdminBidding;
use App\Livewire\AdminVerification;
use App\Livewire\ChatInterface;
use App\Livewire\DriverCockpit;
use App\Livewire\LiveTracking;
use App\Livewire\LoadBidding;
use App\Livewire\SocialFeed;
use App\Livewire\TripHistory;
use App\Livewire\VerificationForm;
use App\Livewire\ReportView;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Social Feed
    Route::get('/feed', SocialFeed::class)->name('feed');

    // Trip History (For all roles)
    Route::get('/history', TripHistory::class)->name('history');

    // Chat
    Route::get('/chat', ChatInterface::class)->name('chat');

    // Verification Form (User submits this to request merchant or driver role)
    Route::get('/verification', VerificationForm::class)->name('verification');

    // Tracking (Can be accessed by Merchant and Administrator)
    Route::get('/tracking', LiveTracking::class)
        ->middleware('role:merchant|administrator')
        ->name('tracking');

    // Admin Routes
    Route::middleware('role:administrator')->group(function () {
        Route::get('/admin/verification', AdminVerification::class)->name('admin.verification');
        Route::get('/admin/bidding', AdminBidding::class)->name('admin.bidding');
        Route::get('/admin/bidding-settings', \App\Livewire\AdminBiddingSettings::class)->name('admin.bidding-settings');
    });

    // Merchant Routes
    Route::middleware('role:merchant')->group(function () {
        Route::get('/merchant/bidding', LoadBidding::class)->name('merchant.bidding');
        Route::get('/merchant/ad-apply', \App\Livewire\MerchantAdApply::class)->name('merchant.ad-apply');
    });

    // Driver Routes
    Route::middleware('role:driver')->group(function () {
        Route::get('/driver/bidding', LoadBidding::class)->name('driver.bidding');
        Route::get('/cockpit', DriverCockpit::class)->name('cockpit');
    });

    // Report Route (Merchant & Driver)
    Route::middleware('role:merchant|driver')->group(function () {
        Route::get('/reports', \App\Livewire\ReportView::class)->name('reports');
        Route::get('/subscription', \App\Livewire\SubscriptionPayment::class)->name('subscription');
        Route::get('/wallet', \App\Livewire\MyWallet::class)->name('wallet');
        Route::get('/driver-branding', \App\Livewire\DriverBranding::class)->name('branding');
    });
});

require __DIR__.'/auth.php';
