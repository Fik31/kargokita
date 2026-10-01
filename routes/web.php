<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureDeposit;
use App\Livewire\AdminBidding;
use App\Livewire\AdminBiddingSettings;
use App\Livewire\AdminDisputes;
use App\Livewire\AdminVerification;
use App\Livewire\AssessmentDashboard;
use App\Livewire\AssessmentForm;
use App\Livewire\ChatInterface;
use App\Livewire\DriverBranding;
use App\Livewire\DriverCockpit;
use App\Livewire\DriverRegistrationForm;
use App\Livewire\DriverSelfAssessmentForm;
use App\Livewire\HseDriverVerificationForm;
use App\Livewire\HseVerificationList;
use App\Livewire\LiveTracking;
use App\Livewire\LoadBidding;
use App\Livewire\MerchantAdApply;
use App\Livewire\MerchantAssessmentDashboard;
use App\Livewire\MerchantLoadList;
use App\Livewire\MerchantSelfAssessmentForm;
use App\Livewire\MerchantVerificationList;
use App\Livewire\MyWallet;
use App\Livewire\ReportView;
use App\Livewire\SocialFeed;
use App\Livewire\SubscriptionPayment;
use App\Livewire\TripHistory;
use App\Livewire\UserProfile;
use App\Livewire\WaybillView;
use App\Livewire\Cargo3dVisualizer;
use App\Livewire\CommandCenter;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', EnsureDeposit::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/user/{user}', UserProfile::class)->name('user.profile');

    // Social Feed
    Route::get('/feed', SocialFeed::class)->name('feed');

    // Trip History (For all roles)
    Route::get('/history', TripHistory::class)->name('history');

    // Chat
    Route::get('/chat', ChatInterface::class)->name('chat');

    // Merchant Verification Form (Self Assessment)
    Route::get('/verification', MerchantSelfAssessmentForm::class)->name('verification');

    // Driver Registration Form (Bundled Info)
    Route::get('/driver-verification', DriverRegistrationForm::class)->name('driver.verification');

    // Driver HSE Assessment Form (Detailed)
    Route::get('/driver-hse-assessment', DriverSelfAssessmentForm::class)->name('driver.hse-assessment');

    // Tracking (Can be accessed by Merchant and Administrator)
    Route::get('/tracking', LiveTracking::class)
        ->middleware('role:merchant|administrator')
        ->name('tracking');

    // Electronic Waybill (Surat Jalan Elektronik)
    Route::get('/waybill/{trip_id}', WaybillView::class)->name('waybill');

    // 3D Visualizer (For Presentation WOW Factor)
    Route::get('/3d-optimizer', Cargo3dVisualizer::class)->name('3d-optimizer');
    
    // Command Center (For Presentation WOW Factor)
    Route::get('/command-center', CommandCenter::class)->name('command-center');

    // Admin Routes
    Route::middleware('role:administrator')->group(function () {
        Route::get('/admin/merchant-assessment/dashboard', MerchantAssessmentDashboard::class)->name('admin.merchant-assessment.dashboard');
        Route::get('/admin/merchant-verifications', MerchantVerificationList::class)->name('admin.merchant-assessment.verification-list');
        Route::get('/admin/merchant-verify/{assessment_id?}', AssessmentForm::class)->name('admin.merchant-assessment.form');

        Route::get('/admin/verification', AdminVerification::class)->name('admin.verification');
        Route::get('/admin/bidding', AdminBidding::class)->name('admin.bidding');
        Route::get('/admin/bidding-settings', AdminBiddingSettings::class)->name('admin.bidding-settings');
    });

    // HSE / Admin Assessment Routes
    Route::middleware('role:hse|administrator')->group(function () {
        Route::get('/admin/assessment/dashboard', AssessmentDashboard::class)->name('admin.assessment.dashboard');
        Route::get('/admin/assessment/verifications', HseVerificationList::class)->name('admin.assessment.verification-list');
        Route::get('/admin/assessment/verify/{assessment_id?}', AssessmentForm::class)->name('admin.assessment.form');
        Route::get('/admin/assessment/driver-verify/{request_id}', HseDriverVerificationForm::class)->name('admin.assessment.driver-verify');
        Route::get('/admin/assessment/disputes', AdminDisputes::class)->name('admin.assessment.disputes');
    });

    // Bidding Routes (Accessible by auth, handled inside component)
    Route::get('/bursa', LoadBidding::class)->name('bidding');

    // Merchant Routes
    Route::middleware('role:merchant')->group(function () {
        Route::get('/bursa/create', LoadBidding::class)->name('bidding.create');
        Route::get('/merchant/ad-apply', MerchantAdApply::class)->name('merchant.ad-apply');
        Route::get('/merchant/loads', MerchantLoadList::class)->name('merchant.loads');
    });

    // Driver Routes
    Route::middleware('role:driver')->group(function () {
        Route::get('/cockpit', DriverCockpit::class)->name('cockpit');
    });

    // Report Route (Merchant & Driver)
    Route::middleware('role:merchant|driver')->group(function () {
        Route::get('/reports', ReportView::class)->name('reports');
        Route::get('/subscription', SubscriptionPayment::class)->name('subscription');
        Route::get('/wallet', MyWallet::class)->name('wallet');
        Route::get('/driver-branding', DriverBranding::class)->name('branding');
    });
});

require __DIR__.'/auth.php';
