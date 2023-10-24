<?php

use App\Http\Controllers\Pos\POSDashboardController;
use App\Http\Controllers\Pos\POSLoginController;
use App\Http\Controllers\Pos\PosTokenSwapController;
use App\Http\Controllers\Pos\POSFloatHistoryController;
use App\Http\Controllers\Pos\POSSwapHistoryController;
use App\Http\Controllers\Pos\SettingsController;
use App\Http\Controllers\Pos\POSEndOfDayController;
use App\Http\Controllers\Pos\POSSettingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Token\DisburseTokenRequestController;
use App\Http\Controllers\Token\AdminStoreRrTokenController;
use App\Http\Controllers\Token\AdminReceiveTokenStoreController;
use App\Http\Controllers\Token\AdminPulloutTokensController;
use App\Http\Controllers\Token\AdminReceivedPulloutTokensController;
use App\Http\Controllers\Audit\AdminCollectRrTokensController;
use App\Http\Controllers\capsule\AdminCapsuleRefillsController;
use App\Http\Controllers\capsule\AdminCapsuleReturnsController;

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


Route::get('/', function () {
    return redirect('admin/login');
    // return view('welcome');
});

Route::group(['middleware' => ['web']], function() {
    //Disburse Token
    Route::post(config('crudbooster.ADMIN_PATH').'/get-inventory-token',[DisburseTokenRequestController::class, 'checkTokenInventory'])->name('disburse.get.token.inventory');
    Route::post(config('crudbooster.ADMIN_PATH').'/receive_token',[DisburseTokenRequestController::class, 'checkReleasedToken'])->name('check-released-token');
    Route::get(config('crudbooster.ADMIN_PATH').'/store_rr_token/getRequestForPrint/{id}',[AdminStoreRrTokenController::class, 'getRequestForPrint'])->name('for-print');
    Route::get(config('crudbooster.ADMIN_PATH').'/store_rr_token/forPrintUpdate',[AdminStoreRrTokenController::class, 'forPrintUpdate']);

    Route::get('pos_login', [POSLoginController::class, 'index'])->name('login_page');
    Route::post('pos_login_account', [POSLoginController::class, 'authenticate'])->name('login');
    Route::get('pos_logout_account', [POSLoginController::class, 'logout'])->name('logout');
    Route::get('pos_dashboard', [POSDashboardController::class, 'index'])->middleware('auth');
    Route::get('pos_token_swap', [POSTokenSwapController::class, 'index'])->middleware('auth');
    Route::get('pos_swap_history', [POSSwapHistoryController::class, 'index'])->middleware('auth');
    Route::get('pos_float_history', [POSFloatHistoryController::class, 'index'])->middleware('auth');
    Route::get('pos_settings', [POSSettingsController::class, 'index'])->middleware('auth');
    Route::get('pos_end_of_day', [POSEndOfDayController::class, 'index'])->middleware('auth');
    Route::get(config('crudbooster.ADMIN_PATH').'/receive_token/getReceivingToken/{id}',[AdminReceiveTokenStoreController::class, 'getReceivingToken'])->name('get-receiving-token');

    //Collected Tokens
    Route::post(config('crudbooster.ADMIN_PATH').'/add-collect-token/get-options-machines',[AdminCollectRrTokensController::class, 'getOptionMachines'])->name('get-options-machines');
    Route::get(config('crudbooster.ADMIN_PATH').'/collect_rr_tokens/get-edit/{id}', [AdminCollectRrTokensController::class, 'getEdit']); 

    //Pullout
    Route::get(config('crudbooster.ADMIN_PATH').'/pullout_tokens/getPulloutForPrint/{id}',[AdminPulloutTokensController::class, 'getPulloutForPrint'])->name('pullout-for-print');
    Route::get(config('crudbooster.ADMIN_PATH').'/pullout_tokens/forPrintPulloutUpdate',[AdminPulloutTokensController::class, 'forPrintPulloutUpdate']);
    Route::get(config('crudbooster.ADMIN_PATH').'/received_pullout_tokens/getReceivingPulloutToken/{id}',[AdminReceivedPulloutTokensController::class, 'getReceivingPulloutToken'])->name('get-receiving-pullout-token');

    // Capsules
    Route::post('admin/capsule_refills/submit-capsule-refill', [AdminCapsuleRefillsController::class, 'submitCapsuleRefill'])->name('submit_capsule_refill');
    Route::post('admin/capsule_refills/submit-capsule-return', [AdminCapsuleReturnsController::class, 'submitCapsuleReturn'])->name('submit_capsule_return');
    Route::post('admin/capsule_refills/validate-gasha-machine', [AdminCapsuleReturnsController::class, 'validateGashaMachine'])->name('validate_gasha_machine');

});
