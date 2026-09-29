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

Route::middleware(['auth', \App\Http\Middleware\EnsureDeposit::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Social Feed
    Route::get('/feed', SocialFeed::class)->name('feed');

    // Trip History (For all roles)
    Route::get('/history', TripHistory::class)->name('history');

    // Chat
    Route::get('/chat', ChatInterface::class)->name('chat');

    // Merchant Verification Form (Self Assessment)
    Route::get('/verification', \App\Livewire\MerchantSelfAssessmentForm::class)->name('verification');

    // Driver Verification Form (Self Assessment)
    Route::get('/driver-verification', \App\Livewire\DriverSelfAssessmentForm::class)->name('driver.verification');

    // Tracking (Can be accessed by Merchant and Administrator)
    Route::get('/tracking', LiveTracking::class)
        ->middleware('role:merchant|administrator')
        ->name('tracking');

    // Admin Routes
    Route::middleware('role:administrator')->group(function () {
        Route::get('/admin/merchant-assessment/dashboard', \App\Livewire\MerchantAssessmentDashboard::class)->name('admin.merchant-assessment.dashboard');
        Route::get('/admin/merchant-verifications', \App\Livewire\MerchantVerificationList::class)->name('admin.merchant-assessment.verification-list');
        Route::get('/admin/merchant-verify/{assessment_id?}', \App\Livewire\AssessmentForm::class)->name('admin.merchant-assessment.form');
        
        Route::get('/admin/verification', AdminVerification::class)->name('admin.verification');
        Route::get('/admin/bidding', AdminBidding::class)->name('admin.bidding');
        Route::get('/admin/bidding-settings', \App\Livewire\AdminBiddingSettings::class)->name('admin.bidding-settings');
    });

    // HSE / Admin Assessment Routes
    Route::middleware('role:hse|administrator')->group(function () {
        Route::get('/admin/assessment/dashboard', \App\Livewire\AssessmentDashboard::class)->name('admin.assessment.dashboard');
        Route::get('/admin/assessment/verifications', \App\Livewire\HseVerificationList::class)->name('admin.assessment.verification-list');
        Route::get('/admin/assessment/verify/{assessment_id?}', \App\Livewire\AssessmentForm::class)->name('admin.assessment.form');
    });

    // Bidding Routes (Accessible by auth, handled inside component)
    Route::get('/bursa', LoadBidding::class)->name('bidding');

    // Merchant Routes
    Route::middleware('role:merchant')->group(function () {
        Route::get('/merchant/ad-apply', \App\Livewire\MerchantAdApply::class)->name('merchant.ad-apply');
    });

    // Driver Routes
    Route::middleware('role:driver')->group(function () {
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
