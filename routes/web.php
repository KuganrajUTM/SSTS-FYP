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

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/main', function () {
    $user = Auth::user();
    $driver = null;
    $parentDriverIds = [];

    if ($user && $user->role === 'D') {
        $driver = $user->driver;
    }

    $schoolNames = [];

    if ($user && $user->role === 'P') {
        $parent = $user->parent;
        if ($parent) {
            $parentDriverIds = $parent->children->pluck('driver_id')->filter()->unique()->values();
            $schoolNames     = $parent->children->pluck('school_name')->filter()->unique()->values()->toArray();
        }
    }

    return view('main-dash', [
        'driver'          => $driver,
        'userName'        => $user->name ?? '',
        'parentDriverIds' => $parentDriverIds,
        'schoolNames'     => $schoolNames,
        'userRole'        => $user->role ?? null,
    ]);
})->name('main');

Route::post('/driver/update-location-info', [ProfileController::class, 'updateLocationInfo'])->name('driver.update-location-info');
Route::post('/driver/location', [DriverLocationController::class, 'update'])->name('driver.location.update');
Route::delete('/driver/location', [DriverLocationController::class, 'clear'])->name('driver.location.clear');
Route::get('/driver/location/{driver_id}', [DriverLocationController::class, 'get'])->name('driver.location.get');

Route::get('/driver', [VerificationController::class, 'index'])->name('driver_verification');


// Driver key — public endpoints
Route::post('/validate-driver-key', [DriverKeyController::class, 'validateKey'])->name('driver-key.validate');
Route::get('/driver-key-request', [DriverKeyController::class, 'showRequest'])->name('driver-key.request.show');
Route::post('/driver-key-request', [DriverKeyController::class, 'storeRequest'])->name('driver-key.request');

// Admin — driver key management
Route::get('/admin/driver-keys', [DriverKeyController::class, 'index'])->name('admin.driver-keys');
Route::post('/admin/driver-keys', [DriverKeyController::class, 'store'])->name('admin.driver-keys.store');
Route::delete('/admin/driver-keys/{id}', [DriverKeyController::class, 'destroy'])->name('admin.driver-keys.destroy');
Route::post('/admin/driver-keys/{id}/send', [DriverKeyController::class, 'sendKey'])->name('admin.driver-keys.send');
Route::get('/admin/driver-key-requests/{id}/license', [DriverKeyController::class, 'viewLicense'])->name('admin.driver-key-request.license');

Route::get('/register', function () {
    return view('register');
})->name('register');
Route::get('/register/driver', [AuthController::class, 'showDriverRegister'])->name('driver-register-page');

Route::post('/user-register', [AuthController::class, 'Par_register'])->name('user-register');

Route::post('/driver-register', [AuthController::class, 'Driver_register'])->name('driver-register');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/forgot-password', [ForgetPasswordManager::class, 'view_password'])->name('forgot-password');
Route::post('/forgot-password', [ForgetPasswordManager::class, 'reset_password'])->name('forgot-password-post');

Route::get('/reset-password/{token}', [ForgetPasswordManager::class, 'resetPassword'])->name('reset-password');
Route::post('/reset-password', [ForgetPasswordManager::class, 'updatePassword'])->name('reset-password-post');

Route::middleware(['web'])->group(function () {
    Route::post('/user-login', [AuthController::class, 'user_login'])->name('user-login');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
});


// Route to list all drivers
Route::get('/drivers/{child_id}', [DriverController::class, 'index'])->name('list');

// Route to display details of a specific driver
Route::get('/drivers/{driverId}/{childId}', [DriverController::class, 'detail'])->name('detail');

// Route to select a driver (specific use case)
Route::get('/driver/select/{child}', [DriverController::class, 'select'])->name('select');
Route::post('/children/{child_id}/drivers/{driver_id}/add', [DriverController::class, 'addDriverToChild'])->name('child.addDriver');

Route::delete('/profile/remove', [ProfileController::class, 'removeAccount'])->name('profile.remove');

Route::get('/edit',function(){
    return view('Profile.edit');
})->name('editProfile');

Route::get('/select',function(){
    return view('parent_driver');
})->name('selection');

Route::post('/addsch',[ScheduleController::class, 'Add_Schedule'])->name('Add_Schedule');

Route::post('/verification/rejection/{driverId}', [VerificationController::class, 'saveRejectionReason'])->name('save.rejection.reason');
Route::post('/verification/status/{driverId}', [VerificationController::class, 'updateStatus'])->name('update_verification');
Route::post('/verification/license-expiry/{driverId}', [VerificationController::class, 'updateLicenseExpiry'])->name('verification.license-expiry');
Route::delete('/verification/delete/{driverId}', [VerificationController::class, 'delete'])->name('verification.delete');

Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
Route::get('/schedules/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
Route::put('/schedules/update', [ScheduleController::class, 'update'])->name('schedules.update');
Route::delete('/schedules', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

Route::get('/pay' , [PaymentController::class, 'parent_pay'])->name('parent_pay'); //Parents or parents view all their children's payments (Pending, Overdue and Paid payments)
Route::get('/pay/view/{id}' , [PaymentController::class, 'view_pay'])->name('view_pay'); //Parents view the all the specific details for the chosen row from table
Route::get('/pay/invoice/{id}' , [PaymentController::class, 'inv'])->name('inv'); //Parents view and print invoice

Route::get('/driver-pay', [PaymentController::class, 'select_pay'])->name('driver-pay'); //Driver view the parents to issue payment

Route::get('/driver-app/{id}', [PaymentController::class, 'issue_pay'])->name('driver-app'); //Driver issue the payment for current month
Route::post('/pay-store', [PaymentController::class, 'create_pay'])->name('pay-store'); //Store the issued payment in database

Route::get('/driver-edit/{id}', [PaymentController::class, 'issue_edit'])->name('driver-edit'); //Edit the issued payment
Route::post('/pay-edit', [PaymentController::class, 'edit_pay'])->name('pay-edit'); //Update the payment amount in the database

Route::post('/billplz/callback', [WebhookController::class, 'handleWebhook'])->name('billplz.callback');

Route::get('/checkout/{id}', [PaymentController::class, 'checkout'])->name('payment.checkout'); //Display payment checkout page
Route::post('/checkout-pay/{id}', [PaymentController::class, 'processPayment'])->name('payment.process'); //Send the checkout for processing stripe chekout
Route::get('/sucess', [PaymentController::class, 'success'])->name('success'); //Display payment success page
Route::get('/cancel', [PaymentController::class, 'cancel'])->name('cancel'); //Display payment failed page

Route::get('/fpx/{id}', [PaymentController::class, 'showFPX'])->name('fpx.show');
Route::post('/fpx/{id}', [PaymentController::class, 'processFPX'])->name('fpx.process');

Route::get('/qr-pay/{id}', [PaymentController::class, 'showQRPay'])->name('qr.pay');
Route::post('/qr-pay/{id}/upload', [PaymentController::class, 'uploadQRProof'])->name('qr.upload');

Route::get('/receipt', [PaymentController::class, 'getReceipt'])->name('receipt'); //Parent view all receipts 
Route::get('/receipt/{id}', [PaymentController::class, 'viewReceipt'])->name('view-receipt'); //Parent view specific receipt

Route::put('/payment/status/{id}', [PaymentController::class, 'cashPayment'])->name('payment.cash'); //Admin/driver update payment status for parent who pay via cash


Route::get('/ann', [AnnController::class, 'index'])->name('ann');        // View all announcements
Route::get('/ann/create', [AnnController::class, 'create'])->name('ann.create'); // Add announcement form
Route::post('/ann', [AnnController::class, 'store'])->name('addann');           // Store announcement
Route::get('/ann/{id}/edit', [AnnController::class, 'edit'])->name('ann.edit'); // Edit announcement form
Route::put('/ann/{id}', [AnnController::class, 'update'])->name('ann.update');  // Update announcement
Route::delete('/ann/{id}', [AnnController::class, 'destroy'])->name('ann.destroy'); // Delete announcement


Route::get('/document', [DriverController::class, 'view_pdf'])->name('view-pdf');     

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
});



// Route to list all drivers
Route::get('/drivers/{child_id}', [DriverController::class, 'index'])->name('list');

// Route to display details of a specific driver
Route::get('/drivers/{driverId}/{childId}', [DriverController::class, 'detail'])->name('detail');

// Route to select a driver (specific use case)
Route::get('/driver/select/{child}', [DriverController::class, 'select'])->name('select');
Route::get('/assign-driver/{child_id}/{driver_id}', [DriverController::class, 'addDriverToChild'])->name('assign.driver');


Route::delete('/profile/remove', [ProfileController::class, 'removeAccount'])->name('profile.remove');

Route::get('/schedule/{driver_id}/{child_id}', [ScheduleController::class, 'viewSchedule'])->name('schedule');

Route::post('/assign-driver/{child_id}/{driver_id}', [DriverController::class, 'addDriverToChild'])->name('assign.driver.post');

Route::get('/admin/payments', [AdminController::class, 'paymentRecords'])->name('admin.payments');
Route::delete('/admin/child/{id}/unassign', [AdminController::class, 'unassignChild'])->name('admin.child.unassign');
Route::get('/admin/users', [AdminController::class, 'viewUsers'])->name('admin.users');
Route::get('/admin/sos', [AdminController::class, 'sosIndex'])->name('admin.sos');
Route::delete('/admin/sos/{id}', [AdminController::class, 'sosDestroy'])->name('admin.sos.destroy');
Route::get('/user/{id}/details', [AdminController::class, 'userDetails'])->name('user.details');
Route::post('/admin/assign-driver/{childId}', [AdminController::class, 'assignDriver'])->name('admin.assign-driver');
Route::get('/admin/recommend-driver/{parentId}', [AdminController::class, 'recommendDriver'])->name('admin.recommend-driver');

Route::get('/admin/salary', [AdminController::class, 'salaryIndex'])->name('admin.salary');
Route::post('/admin/salary', [AdminController::class, 'storeSalary'])->name('admin.salary.store');
Route::post('/admin/salary/{id}/receipt', [AdminController::class, 'uploadSalaryReceipt'])->name('admin.salary.receipt');

Route::get('/driver/salary', [AdminController::class, 'driverSalaryIndex'])->name('driver.salary');

Route::get('/addsch',function(){
    return view('schedule.add');
})->name('AddSchedule');

Route::post('/sos', [SosController::class, 'store'])->name('sos.store');
Route::post('/sos/{id}', [SosController::class, 'destroy'])->name('sos.destroy');
Route::get('/parent/sos', [SosController::class, 'parentIndex'])->name('parent.sos');
Route::delete('/parent/sos/{id}', [SosController::class, 'parentDestroy'])->name('parent.sos.destroy');

// Feedback
Route::get('/feedback/parent', [FeedbackController::class, 'parentIndex'])->name('feedback.parent');
Route::post('/feedback/parent', [FeedbackController::class, 'parentStore'])->name('feedback.parent.store');
Route::get('/feedback/driver', [FeedbackController::class, 'driverIndex'])->name('feedback.driver');
Route::post('/feedback/driver', [FeedbackController::class, 'driverStore'])->name('feedback.driver.store');
Route::get('/admin/feedback', [FeedbackController::class, 'adminIndex'])->name('admin.feedback');
Route::post('/admin/feedback/{id}/review', [FeedbackController::class, 'review'])->name('admin.feedback.review');

// Vehicle management (Admin only)
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('vehicles.create');
Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
Route::get('/vehicles/{id}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
Route::put('/vehicles/{id}', [VehicleController::class, 'update'])->name('vehicles.update');
Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');

