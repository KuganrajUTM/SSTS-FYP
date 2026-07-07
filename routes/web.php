<?php

use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DriverLocationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ForgetPasswordManager;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\SosController;
use App\Http\Controllers\DriverKeyController;
use App\Http\Controllers\FeedbackController;

// ──────────────────────────────────────────────
// Public routes (no auth required)
// ──────────────────────────────────────────────

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('main');
    }
    return view('login');
})->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/register/driver', [AuthController::class, 'showDriverRegister'])->name('driver-register-page');

Route::post('/user-register', [AuthController::class, 'Par_register'])->name('user-register');
Route::post('/driver-register', [AuthController::class, 'Driver_register'])->name('driver-register');

Route::middleware(['web'])->group(function () {
    Route::post('/user-login', [AuthController::class, 'user_login'])->name('user-login');
});

Route::get('/forgot-password', [ForgetPasswordManager::class, 'view_password'])->name('forgot-password');
Route::post('/forgot-password', [ForgetPasswordManager::class, 'reset_password'])->name('forgot-password-post');
Route::get('/reset-password/{token}', [ForgetPasswordManager::class, 'resetPassword'])->name('reset-password');
Route::post('/reset-password', [ForgetPasswordManager::class, 'updatePassword'])->name('reset-password-post');

// Billplz webhook — must be public (no CSRF / auth)
Route::post('/billplz/callback', [WebhookController::class, 'handleWebhook'])->name('billplz.callback');

// Driver key request — public so unregistered drivers can apply
Route::post('/validate-driver-key', [DriverKeyController::class, 'validateKey'])->name('driver-key.validate');
Route::get('/driver-key-request', [DriverKeyController::class, 'showRequest'])->name('driver-key.request.show');
Route::post('/driver-key-request', [DriverKeyController::class, 'storeRequest'])->name('driver-key.request');

// ──────────────────────────────────────────────
// Authenticated routes
// ──────────────────────────────────────────────

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/main', function () {
        $user   = Auth::user();
        $driver = null;
        $parentDriverIds   = [];
        $profileIncomplete = false;
        $schoolNames       = [];

        if ($user && $user->role === 'D') {
            $driver = $user->driver;
            if ($driver) {
                $profileIncomplete = empty($driver->VRN) || empty($driver->city) || empty($driver->district);
            }
        }

        if ($user && $user->role === 'P') {
            $parent = $user->parent;
            if ($parent) {
                $profileIncomplete = empty($parent->phone) || empty($parent->location) || empty($parent->city) || empty($parent->district);
                $parentDriverIds   = $parent->children->pluck('driver_id')->filter()->unique()->values();
                $schoolNames       = $parent->children->pluck('school_name')->filter()->unique()->values()->toArray();
            }
        }

        return view('main-dash', [
            'driver'            => $driver,
            'userName'          => $user->name ?? '',
            'parentDriverIds'   => $parentDriverIds,
            'schoolNames'       => $schoolNames,
            'userRole'          => $user->role ?? null,
            'profileIncomplete' => $profileIncomplete,
        ]);
    })->name('main');

    // Driver live location (parent polls this — accessible to all authenticated users)
    Route::get('/driver/location/{driver_id}', [DriverLocationController::class, 'get'])->name('driver.location.get');

    // Driver verification
    Route::get('/driver', [VerificationController::class, 'index'])->name('driver_verification');
    Route::post('/verification/rejection/{driverId}', [VerificationController::class, 'saveRejectionReason'])->name('save.rejection.reason');
    Route::post('/verification/status/{driverId}', [VerificationController::class, 'updateStatus'])->name('update_verification');
    Route::post('/verification/license-expiry/{driverId}', [VerificationController::class, 'updateLicenseExpiry'])->name('verification.license-expiry');
    Route::delete('/verification/delete/{driverId}', [VerificationController::class, 'delete'])->name('verification.delete');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/remove', [ProfileController::class, 'removeAccount'])->name('profile.remove');

    // Edit profile (legacy route)
    Route::get('/edit', function () {
        return view('Profile.edit');
    })->name('editProfile');

    Route::get('/select', function () {
        return view('parent_driver');
    })->name('selection');

    // Drivers (parent selects driver)
    Route::get('/drivers/{child_id}', [DriverController::class, 'index'])->name('list');
    Route::get('/drivers/{driverId}/{childId}', [DriverController::class, 'detail'])->name('detail');
    Route::get('/driver/select/{child}', [DriverController::class, 'select'])->name('select');
    Route::post('/children/{child_id}/drivers/{driver_id}/add', [DriverController::class, 'addDriverToChild'])->name('child.addDriver');
    Route::get('/assign-driver/{child_id}/{driver_id}', [DriverController::class, 'addDriverToChild'])->name('assign.driver');
    Route::post('/assign-driver/{child_id}/{driver_id}', [DriverController::class, 'addDriverToChild'])->name('assign.driver.post');
    Route::get('/document', [DriverController::class, 'view_pdf'])->name('view-pdf');

    // Schedules
    Route::get('/addsch', function () {
        return view('schedule.add');
    })->name('AddSchedule');
    Route::post('/addsch', [ScheduleController::class, 'Add_Schedule'])->name('Add_Schedule');
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('/schedules/update', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::get('/schedule/{driver_id}/{child_id}', [ScheduleController::class, 'viewSchedule'])->name('schedule');

    // Payments
    Route::get('/pay', [PaymentController::class, 'parent_pay'])->name('parent_pay');
    Route::get('/pay/view/{id}', [PaymentController::class, 'view_pay'])->name('view_pay');
    Route::get('/pay/invoice/{id}', [PaymentController::class, 'inv'])->name('inv');
    Route::get('/driver-pay', [PaymentController::class, 'select_pay'])->name('driver-pay');
    Route::get('/driver-app/{id}', [PaymentController::class, 'issue_pay'])->name('driver-app');
    Route::post('/pay-store', [PaymentController::class, 'create_pay'])->name('pay-store');
    Route::get('/driver-edit/{id}', [PaymentController::class, 'issue_edit'])->name('driver-edit');
    Route::post('/pay-edit', [PaymentController::class, 'edit_pay'])->name('pay-edit');
    Route::get('/checkout/{id}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/checkout-pay/{id}', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/sucess', [PaymentController::class, 'success'])->name('success');
    Route::get('/cancel', [PaymentController::class, 'cancel'])->name('cancel');
    Route::get('/fpx/{id}', [PaymentController::class, 'showFPX'])->name('fpx.show');
    Route::post('/fpx/{id}', [PaymentController::class, 'processFPX'])->name('fpx.process');
    Route::get('/qr-pay/{id}', [PaymentController::class, 'showQRPay'])->name('qr.pay');
    Route::post('/qr-pay/{id}/upload', [PaymentController::class, 'uploadQRProof'])->name('qr.upload');
    Route::get('/receipt', [PaymentController::class, 'getReceipt'])->name('receipt');
    Route::get('/receipt/{id}', [PaymentController::class, 'viewReceipt'])->name('view-receipt');
    Route::put('/payment/status/{id}', [PaymentController::class, 'cashPayment'])->name('payment.cash');

    // Announcements
    Route::get('/ann', [AnnController::class, 'index'])->name('ann');
    Route::get('/ann/create', [AnnController::class, 'create'])->name('ann.create');
    Route::post('/ann', [AnnController::class, 'store'])->name('addann');
    Route::get('/ann/{id}/edit', [AnnController::class, 'edit'])->name('ann.edit');
    Route::put('/ann/{id}', [AnnController::class, 'update'])->name('ann.update');
    Route::delete('/ann/{id}', [AnnController::class, 'destroy'])->name('ann.destroy');

    // SOS
    Route::post('/sos', [SosController::class, 'store'])->name('sos.store');
    Route::post('/sos/{id}', [SosController::class, 'destroy'])->name('sos.destroy');
    Route::get('/parent/sos', [SosController::class, 'parentIndex'])->name('parent.sos');
    Route::delete('/parent/sos/{id}', [SosController::class, 'parentDestroy'])->name('parent.sos.destroy');

    // Feedback (parent & driver)
    Route::get('/feedback/parent', [FeedbackController::class, 'parentIndex'])->name('feedback.parent');
    Route::post('/feedback/parent', [FeedbackController::class, 'parentStore'])->name('feedback.parent.store');
    Route::get('/feedback/driver', [FeedbackController::class, 'driverIndex'])->name('feedback.driver');
    Route::post('/feedback/driver', [FeedbackController::class, 'driverStore'])->name('feedback.driver.store');

    // Admin only routes
    Route::middleware('role:A')->group(function () {
        Route::get('/admin/driver-keys', [DriverKeyController::class, 'index'])->name('admin.driver-keys');
        Route::post('/admin/driver-keys', [DriverKeyController::class, 'store'])->name('admin.driver-keys.store');
        Route::delete('/admin/driver-keys/{id}', [DriverKeyController::class, 'destroy'])->name('admin.driver-keys.destroy');
        Route::post('/admin/driver-keys/{id}/send', [DriverKeyController::class, 'sendKey'])->name('admin.driver-keys.send');
        Route::get('/admin/driver-key-requests/{id}/license', [DriverKeyController::class, 'viewLicense'])->name('admin.driver-key-request.license');

        Route::get('/admin/payments', [AdminController::class, 'paymentRecords'])->name('admin.payments');
        Route::delete('/admin/child/{id}/unassign', [AdminController::class, 'unassignChild'])->name('admin.child.unassign');
        Route::get('/admin/users', [AdminController::class, 'viewUsers'])->name('admin.users');
        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.destroy');
        Route::get('/admin/sos', [AdminController::class, 'sosIndex'])->name('admin.sos');
        Route::delete('/admin/sos/{id}', [AdminController::class, 'sosDestroy'])->name('admin.sos.destroy');
        Route::get('/user/{id}/details', [AdminController::class, 'userDetails'])->name('user.details');
        Route::post('/admin/assign-driver/{childId}', [AdminController::class, 'assignDriver'])->name('admin.assign-driver');
        Route::get('/admin/recommend-driver/{parentId}', [AdminController::class, 'recommendDriver'])->name('admin.recommend-driver');

        Route::get('/admin/salary', [AdminController::class, 'salaryIndex'])->name('admin.salary');
        Route::post('/admin/salary', [AdminController::class, 'storeSalary'])->name('admin.salary.store');
        Route::post('/admin/salary/{id}/receipt', [AdminController::class, 'uploadSalaryReceipt'])->name('admin.salary.receipt');
        Route::get('/admin/salary/{id}/payslip', [AdminController::class, 'downloadPayslip'])->name('admin.salary.payslip');

        Route::get('/admin/feedback', [FeedbackController::class, 'adminIndex'])->name('admin.feedback');
        Route::post('/admin/feedback/{id}/review', [FeedbackController::class, 'review'])->name('admin.feedback.review');

        Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
        Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('vehicles.create');
        Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
        Route::get('/vehicles/{id}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
        Route::put('/vehicles/{id}', [VehicleController::class, 'update'])->name('vehicles.update');
        Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
    });

    // Driver only routes
    Route::middleware('role:D')->group(function () {
        Route::get('/driver/salary', [AdminController::class, 'driverSalaryIndex'])->name('driver.salary');
        Route::get('/driver/salary/{id}/payslip', [AdminController::class, 'downloadPayslip'])->name('driver.salary.payslip');
        Route::post('/driver/location', [DriverLocationController::class, 'update'])->name('driver.location.update');
        Route::delete('/driver/location', [DriverLocationController::class, 'clear'])->name('driver.location.clear');
        Route::post('/driver/update-location-info', [ProfileController::class, 'updateLocationInfo'])->name('driver.update-location-info');
    });

});
