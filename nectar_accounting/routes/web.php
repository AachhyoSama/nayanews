<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CancelledVoucherController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChildAccountController;
use App\Http\Controllers\DailyExpensesController;
use App\Http\Controllers\JournalImageController;
use App\Http\Controllers\JournalVouchersController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SubAccountController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Models\ChildAccount;
use App\Models\ProductImages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

 Route::group(['middleware' => ['auth']], function() {
     // Users
    Route::resource('user', UserController::class);
    Route::get('deletedusers', [UserController::class, 'deletedusers'])->name('deletedusers');
    Route::get('restoreusers/{id}', [UserController::class, 'restoreusers'])->name('restoreusers');

    // Permissions
    Route::resource('permission', PermissionController::class);

    // Settings
    Route::resource('setting', SettingController::class);

    // Roles
    Route::resource('roles', RoleController::class);
    Route::get('deletedroles', [RoleController::class, 'deletedroles'])->name('deletedroles');
    Route::get('restoreroles/{id}', [RoleController::class, 'restoreroles'])->name('restoreroles');
 });

 // Account Heads
Route::resource('account', AccountController::class);
Route::get('restoreaccount/{id}', [AccountController::class, 'restore'])->name('restore');
Route::get('deletedindex', [AccountController::class, 'deletedindex'])->name('deletedindex');

// Sub Account Heads
Route::resource('sub_account', SubAccountController::class);
Route::get('restoresubaccount/{id}', [SubAccountController::class, 'restoresubaccount'])->name('restoresubaccount');
Route::get('deletedsubindex', [SubAccountController::class, 'deletedsubindex'])->name('deletedsubindex');

// Child Account Types
Route::resource('child_account', ChildAccountController::class);
Route::get('restorechildaccount/{id}', [ChildAccountController::class, 'restorechildaccount'])->name('restorechildaccount');
Route::get('deletedchildindex', [ChildAccountController::class, 'deletedchildindex'])->name('deletedchildindex');

// Journal Voucher
Route::resource('journals', JournalVouchersController::class);
Route::post('journals/status/{id}', [JournalVouchersController::class, 'status'])->name('journals.status');
Route::post('journals/cancel/{id}', [JournalVouchersController::class, 'cancel'])->name('journals.cancel');
Route::post('journals/revive/{id}', [JournalVouchersController::class, 'revive'])->name('journals.revive');
Route::get('journals/print/{id}', [JournalVouchersController::class, 'printpreview'])->name('journals.print');

Route::get('cancelledjournals', [JournalVouchersController::class, 'cancelledindex'])->name('journals.cancelled');
Route::get('unapprovedjournals', [JournalVouchersController::class, 'unapprovedindex'])->name('journals.unapproved');
Route::resource('cancelledvoucher', CancelledVoucherController::class);

//Trialbalance
Route::get('/trialbalance', [JournalVouchersController::class, 'trialbalance'])->name('journals.trialbalance');

Route::resource('journalimage', JournalImageController::class);

// Vendors
Route::resource('vendors', VendorController::class);
Route::get('restorevendor/{id}', [VendorController::class, 'restorevendor'])->name('restorevendor');
Route::get('deletedvendor', [VendorController::class, 'deletedvendor'])->name('deletedvendor');

// Daily Expenses
Route::resource('dailyexpenses', DailyExpensesController::class);
Route::get('restoreexpenses/{id}', [DailyExpensesController::class, 'restoreexpenses'])->name('restoreexpenses');
Route::get('deletedexpenses', [DailyExpensesController::class, 'deletedexpenses'])->name('deletedexpenses');

// Category
Route::resource('category', CategoryController::class);

// Product
Route::resource('product', ProductController::class);
Route::delete('productimage/{id}', [ProductController::class, 'deleteproductimage'])->name('deleteproductimage');
Route::get('restoreproduct/{id}', [ProductController::class, 'restoreproduct'])->name('restoreproduct');
Route::get('deletedproduct', [ProductController::class, 'deletedproduct'])->name('deletedproduct');

// Service
Route::resource('service', ServiceController::class);
Route::delete('serviceimage/{id}', [ServiceController::class, 'deleteserviceimage'])->name('deleteserviceimage');
Route::get('restoreservice/{id}', [ServiceController::class, 'restoreservice'])->name('restoreservice');
Route::get('deletedservice', [ServiceController::class, 'deletedservice'])->name('deletedservice');

// Generate Journal Report
Route::get('extra', [JournalVouchersController::class, 'extra'])->name('extra');
Route::get('report/{id}/{starting_date}/{ending_date}', [JournalVouchersController::class, 'generatereport'])->name('generatereport');

// Fetch district data
Route::get('vendors/getdistricts/{id}', [VendorController::class, 'getdistricts'])->name('getdistricts');

// Generate Trial Balance Report
Route::get('trialextra', [JournalVouchersController::class, 'trialextra'])->name('trialextra');
Route::get('trialreport/{id}/{starting_date}/{ending_date}', [JournalVouchersController::class, 'generatetrialreport'])->name('generatetrialreport');

// Download Journal PDF
Route::get('pdf/generateJournal/{id}', [JournalVouchersController::class, 'generateJournalPDF'])->name('pdf.generateJournal');
