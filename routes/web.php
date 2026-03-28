<?php

use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ForgetPasswordManager;
use App\Http\Controllers\ProfileController;
use App\Models\Driver;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/main', function () {
    return view('main-dash');
})->name('main');

Route::get('/driver', [VerificationController::class, 'index'])->name('driver_verification');


Route::get('/register', function () {
    return view('register');
})->name('register');

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

Route::post('/verification/rejection/{driverId}', [VerificationController::class, 'saveRejectionReason']);
Route::post('/verification/status/{driverId}', [VerificationController::class, 'updateStatus']);
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

Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook'])->name('cashier.webhook'); //Handle webhook (after and before payments)

Route::get('/checkout/{id}', [PaymentController::class, 'checkout'])->name('payment.checkout'); //Display payment checkout page
Route::post('/checkout-pay/{id}', [PaymentController::class, 'processPayment'])->name('payment.process'); //Send the checkout for processing stripe chekout
Route::get('/sucess', [PaymentController::class, 'success'])->name('success'); //Display payment success page
Route::get('/cancel', [PaymentController::class, 'cancel'])->name('cancel'); //Display payment failed page

Route::get('/receipt', [PaymentController::class, 'getReceipt'])->name('receipt'); //Parent view all receipts 
Route::get('/receipt/{id}', [PaymentController::class, 'viewReceipt'])->name('view-receipt'); //Parent view specific receipt

Route::put('/payment/status/{id}', [PaymentController::class, 'cashPayment']); //Driver update payment status for parent who pay via cash


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

Route::post('/assign-driver/{child_id}/{driver_id}', [DriverController::class, 'addDriverToChild'])->name('assign.driver');

Route::get('/admin/users', [AdminController::class, 'viewUsers'])->name('admin.users');
Route::get('/user/{id}/details', [AdminController::class, 'userDetails'])->name('user.details');

Route::get('/addsch',function(){
    return view('schedule.add');
})->name('AddSchedule');

