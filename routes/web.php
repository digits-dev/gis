<?php

use App\Http\Controllers\Pos\POSDashboardController;
use App\Http\Controllers\Pos\POSLoginController;
use App\Http\Controllers\Pos\POSTokenSwapController;
use App\Http\Controllers\Pos\POSFloatHistoryController;
use App\Http\Controllers\Pos\POSSwapHistoryController;
use App\Http\Controllers\Pos\SettingsController;
use App\Http\Controllers\Pos\POSEndOfDayController;
use App\Http\Controllers\Pos\POSSettingsController;
use App\Http\Controllers\Pos\POSTokenDispenseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Token\DisburseTokenRequestController;
use App\Http\Controllers\Token\AdminStoreRrTokenController;
use App\Http\Controllers\Token\AdminReceiveTokenStoreController;
use App\Http\Controllers\Token\AdminPulloutTokensController;
use App\Http\Controllers\Token\AdminReceivedPulloutTokensController;
use App\Http\Controllers\Token\AdminTokenAdjustmentsController;
use App\Http\Controllers\Audit\AdminCollectTokenApprovalController;
use App\Http\Controllers\Audit\AdminCollectRrTokensController;
use App\Http\Controllers\Capsule\AdminCapsuleRefillsController;
use App\Http\Controllers\Token\AdminCollectRrTokensReceivingController;
use App\Http\Controllers\Token\AdminCollectRrTokenSalesController;
use App\Http\Controllers\Capsule\AdminCapsuleReturnsController;
use App\Http\Controllers\AdminTruncateController;
use App\Http\Controllers\Audit\AdminCycleCountsController;
use App\Http\Controllers\Audit\AdminCycleCountApprovalController;
use App\Http\Controllers\Capsule\AdminCapsuleMergesController;
use App\Http\Controllers\Capsule\AdminCapsuleSalesController;
use App\Http\Controllers\Capsule\AdminCapsuleSplitController;
use App\Http\Controllers\Capsule\AdminInventoryCapsulesController;
use App\Http\Controllers\Submaster\AdminGashaMachinesController;
use App\Http\Controllers\Submaster\AdminImportController;
use App\Http\Controllers\Submaster\AdminAddOnsController;
use App\Http\Controllers\History\AdminSwapHistoriesController;
use App\Http\Controllers\Capsule\AdminCapsuleSwapHeadersController;
use App\Http\Controllers\Capsule\AdminHistoryCapsulesController;
use App\Http\Controllers\Capsule\AdminCapsuleAdjustmentsController;
use App\Http\Controllers\Submaster\AdminItemsController;
use App\Http\Controllers\AdminCmsUsersController;
use App\Http\Controllers\Token\AdminCollectTokenController;

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
    Route::get('pos_logout_account_eod', [POSLoginController::class, 'logoutEOD'])->name('logout_eod');
    Route::get('pos_logout_account_es', [POSLoginController::class, 'endSession'])->name('logout_end_session');
    Route::get('pos_dashboard', [POSDashboardController::class, 'index'])->middleware('auth');
    Route::get('pos_token_swap', [POSTokenSwapController::class, 'index'])->middleware('auth');
    Route::get('pos_token_swap/suggest_jan_number', [POSTokenSwapController::class, 'suggestJanNumber'])->middleware('auth')->name('suggest_jan_number');
    Route::post('pos_token_swap/swap', [POSTokenSwapController::class, 'store'])->middleware('auth')->name('swap');
    Route::get('pos_swap_history', [POSSwapHistoryController::class, 'index'])->middleware('auth');
    Route::get('pos_swap_history/{id}', [POSSwapHistoryController::class, 'show'])->middleware('auth');
    Route::get('pos_swap_history/getDetails/{id}', [POSSwapHistoryController::class, 'getDetails'])->middleware('auth');
    Route::get('pos_swap_history/edit/{id}', [POSSwapHistoryController::class, 'edit'])->middleware('auth');
    Route::get('pos_float_history', [POSFloatHistoryController::class, 'index'])->middleware('auth');
    Route::get('pos_float_history/{id}/view', [POSFloatHistoryController::class, 'viewFloatHistory'])->name('view_float_history');
    Route::get('pos_settings', [POSSettingsController::class, 'index'])->middleware('auth');
    Route::get('pos_end_of_day', [POSEndOfDayController::class, 'index'])->middleware('auth');
    Route::get(config('crudbooster.ADMIN_PATH').'/receive_token/getReceivingToken/{id}',[AdminReceiveTokenStoreController::class, 'getReceivingToken'])->name('get-receiving-token');
    
    //TOKEN DESPENSE
    Route::get('pos_token_dispense', [POSTokenDispenseController::class, 'index'])->middleware('auth');
    Route::post('pos_token_dispense/swap-dispense', [POSTokenDispenseController::class, 'store'])->middleware('auth')->name('swap-dispense');
    //POS Dashboard
    Route::post('admin/dashboard/sod', [POSDashboardController::class, 'submitSOD'])->name('submitSOD');

    //POS EOD
    Route::post('admin/pos_end_of_day/eod', [POSEndOfDayController::class, 'submitEOD'])->name('submitEOD');

    //POS Setting
    Route::post('pos_settings/{id}/updatePassword', [POSSettingsController::class, 'updatePassword'])->name('update_password');

    //Token Adjustment
    Route::post('admin/token_adjustments/view',[AdminTokenAdjustmentsController::class,'viewAmount'])->name('viewAmount');
    Route::post('admin/token_adjustments/submit',[AdminTokenAdjustmentsController::class,'submitAmount'])->name('submitAmount');
    Route::post('admin/token_adjustments/getTokenInventory',[AdminTokenAdjustmentsController::class,'getTokenInventory'])->name('getTokenInventory');

    //Submaster Add Ons
    Route::post('admin/add_ons/get',[AdminAddOnsController::class,'getDescription'])->name('getDescription');
    Route::post('admin/add_ons/submit',[AdminAddOnsController::class,'submitAddOns'])->name('submitAddOns');

    //Collected Tokens
    Route::post(config('crudbooster.ADMIN_PATH').'/add-collect-token/get-options-machines',[AdminCollectRrTokensController::class, 'getOptionMachines'])->name('get-options-machines');
    Route::get(config('crudbooster.ADMIN_PATH').'/collect_rr_tokens_receiving/get-edit/{id}', [AdminCollectRrTokensReceivingController::class, 'getEdit']);
    Route::post(config('crudbooster.ADMIN_PATH').'/collect_rr_tokens/get-machine-collect', [AdminCollectRrTokensController::class, 'getMachine'])->name('get_machine-collect');
    Route::post(config('crudbooster.ADMIN_PATH').'/collect_rr_tokens/check-inventory-qty-collect',[AdminCollectRrTokensController::class, 'checkInventoryQty'])->name('check-inventory-qty-collect');
    
    //Collected Token Approval
    Route::post(config('crudbooster.ADMIN_PATH').'/collect_rr_tokens/approval-collect-token',[AdminCollectTokenApprovalController::class, 'submitApprovalCc'])->name('submit-collect-token-approval'); 
    Route::get(config('crudbooster.ADMIN_PATH').'/collect_rr_tokens/collect-token-edit/{id}',[AdminCollectRrTokensController::class, 'getCollectTokenEdit'])->name('collect-token-edit');
    Route::post(config('crudbooster.ADMIN_PATH').'/collect_rr_tokens/edit-collect-token-file', [AdminCollectTokenApprovalController::class, 'collectTokenFileEdit'])->name('collect-token-edit-file-store');
    Route::post(config('crudbooster.ADMIN_PATH').'/collect_rr_tokens/submit-collect-token-edit', [AdminCollectRrTokensController::class, 'editCollectToken'])->name('submit-collect-token-edit');
    Route::get(config('crudbooster.ADMIN_PATH').'/collect_token_approval/export_collect_token_approval', [AdminCollectTokenApprovalController::class, 'exportTokenApproval'])->name('collect-token-edit-file-store');
    //Cancel Collect Token
    Route::get(config('crudbooster.ADMIN_PATH').'/collect_rr_tokens/collect-token-cancel/{id}',[AdminCollectRrTokensController::class, 'cancelCollectToken'])->name('collect-token-cancel');
    //Temporary Add no of token in collect token line
    Route::get(config('crudbooster.ADMIN_PATH').'/collect_rr_token_sales/update-no-of-token', [AdminCollectRrTokenSalesController::class, 'UploadNoOfToken']);
    Route::post(config('crudbooster.ADMIN_PATH').'/collect_rr_token_sales/upload-no-of-token',[AdminCollectRrTokenSalesController::class, 'saveNoOfToken'])->name('upload-no-of-token');
    
    //Pullout
    Route::get(config('crudbooster.ADMIN_PATH').'/pullout_tokens/getPulloutForPrint/{id}',[AdminPulloutTokensController::class, 'getPulloutForPrint'])->name('pullout-for-print');
    Route::get(config('crudbooster.ADMIN_PATH').'/pullout_tokens/forPrintPulloutUpdate',[AdminPulloutTokensController::class, 'forPrintPulloutUpdate']);
    Route::get(config('crudbooster.ADMIN_PATH').'/received_pullout_tokens/getReceivingPulloutToken/{id}',[AdminReceivedPulloutTokensController::class, 'getReceivingPulloutToken'])->name('get-receiving-pullout-token');

    // Capsules
    Route::post('admin/capsule_refills/submit-capsule-refill', [AdminCapsuleRefillsController::class, 'submitCapsuleRefill'])->name('submit_capsule_refill');
    Route::post('admin/capsule_returns/submit-capsule-return', [AdminCapsuleReturnsController::class, 'submitCapsuleReturn'])->name('submit_capsule_return');
    Route::post('admin/capsule_refills/validate-gasha-machine', [AdminCapsuleReturnsController::class, 'validateGashaMachine'])->name('validate_gasha_machine');
    Route::post('admin/capsule_refills/get-partner-machine', [AdminCapsuleRefillsController::class, 'getPartnerMachine'])->name('get_partner_machine');
    Route::post('admin/capsule_merges/check-machines', [AdminCapsuleMergesController::class, 'checkMachines'])->name('check_machines');
    Route::post('admin/capsule_split/check-machines', [AdminCapsuleSplitController::class, 'checkMachines'])->name('check_split_machines');
    Route::post('admin/capsule_merges/submit-merge', [AdminCapsuleMergesController::class, 'submitMerge'])->name('submit_merge');
    Route::post('admin/capsule_split/submit-split', [AdminCapsuleSplitController::class, 'submitSplit'])->name('submit_split');

    //CAPSULES IMPORT
    Route::get(config('crudbooster.ADMIN_PATH').'/capsule_refills/capsules-upload', [AdminCapsuleRefillsController::class, 'uploadCapsules']);
    Route::post(config('crudbooster.ADMIN_PATH').'/capsule_refills/upload-capsules',[AdminCapsuleRefillsController::class, 'saveUploadCapsules'])->name('upload-capsules');
    Route::get(config('crudbooster.ADMIN_PATH').'/capsule_refills/download-capsules-template',[AdminCapsuleRefillsController::class, 'downloadCapsulesTemplate']);

    //CAPSULE EXPORT
    Route::post('admin/capsule_swap_headers/export', [AdminCapsuleSwapHeadersController::class, 'exportData'])->name('capsule_swap_export');
    Route::post('admin/capsule_merges/export', [AdminCapsuleMergesController::class, 'exportData'])->name('capsule_merge_export');
    Route::post('admin/capsule_split/export', [AdminCapsuleSplitController::class, 'exportData'])->name('capsule_split_export');

    // CAPSULE INVENTORY EXPORT
    Route::post('admin/inventory_capsules/export', [AdminInventoryCapsulesController::class, 'exportData'])->name('capsule_inventory_export');
    Route::post('admin/inventory_capsules/export-with-date', [AdminInventoryCapsulesController::class, 'exportDatawWithDate'])->name('capsule_inventory_export_with_date');
    
    // CAPSULE MOVEMENT HISTORY EXPORT
    Route::post('admin/history_capsules/export', [AdminHistoryCapsulesController::class, 'exportData'])->name('history_capsule_export');
    
    //CAPSULE SALES EXPORT
    Route::post('admin/capsule_sales/export', [AdminCapsuleSalesController::class, 'exportData'])->name('capsule_sales_export');
    Route::post('admin/capsule_sales/export-with-date', [AdminCapsuleSalesController::class, 'exportDataWithDate'])->name('capsule_sales_export_with_date');

    //CAPSULE ADJUSTMENT
    Route::post('admin/capsule_adjustments/view',[AdminCapsuleAdjustmentsController::class,'getJanCode'])->name('getJanCode');
    Route::post('admin/capsule_adjustments_machines/getMachines',[AdminCapsuleAdjustmentsController::class,'getMachines'])->name('getMachines');
    Route::post('admin/capsule_adjustments/getMachinesQty',[AdminCapsuleAdjustmentsController::class,'getMachinesQty'])->name('getMachinesQty');
    Route::post('admin/capsule_adjustments/getCapsuleInventory',[AdminCapsuleAdjustmentsController::class,'getCapsuleInventory'])->name('getCapsuleInventory');
    Route::post('admin/capsule_adjustments/submit-capsule-adjustment',[AdminCapsuleAdjustmentsController::class,'submitCapsuleAmount'])->name('submitCapsuleAmount');
    
    //CYCLE COUNT EXPORT
    Route::post('admin/cycle_counts/export', [AdminCycleCountsController::class, 'exportData'])->name('cycle_count_export');

    //Restricted Route
    // Route::get(config('crudbooster.ADMIN_PATH').'/db-truncate',[AdminTruncateController::class, 'dbtruncate']);

    //Cycle Count
    Route::post(config('crudbooster.ADMIN_PATH').'/cycle_counts/get-machine', [AdminCycleCountsController::class, 'getMachine'])->name('get-machine-cycle-count');
    Route::post(config('crudbooster.ADMIN_PATH').'/cycle_counts/check-inventory-qty',[AdminCycleCountsController::class, 'checkInventoryQty'])->name('check-inventory-qty');
    Route::post(config('crudbooster.ADMIN_PATH').'/cycle_counts/check-sr-inventory-qty',[AdminCycleCountsController::class, 'checkStockRoomInventoryQty'])->name('check-sr-inventory-qty');

    Route::post(config('crudbooster.ADMIN_PATH').'/cycle_counts/submit-capsule-cycle-flr', [AdminCycleCountsController::class, 'submitCycleCountFloor'])->name('submit-cycle-count-floor');
    Route::post(config('crudbooster.ADMIN_PATH').'/cycle_counts/submit-capsule-cycle-sr', [AdminCycleCountsController::class, 'submitCycleCountStockRoom'])->name('submit-cycle-count-sr');
    Route::post(config('crudbooster.ADMIN_PATH').'/cycle_counts/submit-capsule-cycle-sr-file-upload', [AdminCycleCountsController::class, 'importCycleCount'])->name('submit-cycle-count-sr-file-upload');
    Route::post(config('crudbooster.ADMIN_PATH').'/cycle_counts/validate-machine-items', [AdminCycleCountsController::class, 'validateMachineItems'])->name('validate-machine-items');
    Route::get(config('crudbooster.ADMIN_PATH').'/cycle_counts/add-cycle-count-sr', [AdminCycleCountsController::class, 'getAddCycleCountStockRoom'])->name('get-add-cycle-count-stock-room');
    Route::post(config('crudbooster.ADMIN_PATH').'/cycle_counts/get-item-code', [AdminCycleCountsController::class, 'checkItem'])->name('check-item-code');
    Route::post(config('crudbooster.ADMIN_PATH').'/cycle_counts/store-file', [AdminCycleCountsController::class, 'storeFile'])->name('cycle-count-file-store');
    Route::post(config('crudbooster.ADMIN_PATH').'/cycle_counts/store-file-floor', [AdminCycleCountsController::class, 'storeFileFloor'])->name('cycle-count-floor-file-store');
    Route::post(config('crudbooster.ADMIN_PATH').'/cycle_counts/delete-file',[AdminCycleCountsController::class, 'deleteFile'])->name('delete-file'); 

    Route::post(config('crudbooster.ADMIN_PATH').'/cycle_counts/approval-cycle-count',[AdminCycleCountApprovalController::class, 'submitApprovalCc'])->name('submit_approval_cc'); 
    Route::get(config('crudbooster.ADMIN_PATH').'/cycle_counts/get-edit/{id}',[AdminCycleCountsController::class, 'getEdit'])->name('get-edit');
    Route::post(config('crudbooster.ADMIN_PATH').'/cycle_counts/edit-store-file', [AdminCycleCountsController::class, 'storeFileEdit'])->name('cycle-count-edit-file-store');
    Route::post(config('crudbooster.ADMIN_PATH').'/cycle_counts/submit-capsule-cycle-edit', [AdminCycleCountsController::class, 'editCycleCount'])->name('submit-cycle-count-edit');
    
    //GASHA MACHINES IMPORT
    Route::get(config('crudbooster.ADMIN_PATH').'/gasha_machines/machines-upload', [AdminGashaMachinesController::class, 'UploadMachines']);
    Route::post(config('crudbooster.ADMIN_PATH').'/gasha_machines/upload-machines',[AdminImportController::class, 'saveMachines'])->name('upload-machines');
    Route::get(config('crudbooster.ADMIN_PATH').'/gasha_machines/download-machines-template',[AdminImportController::class, 'downloadMachinesTemplate']);

    // GASHA MACHINE EXPORT
    Route::post('admin/gasha_machines/export', [AdminGashaMachinesController::class, 'exportData'])->name('gasha_machines_export');


    //SWAP HISTORY VOID BACKEND
    Route::get(config('crudbooster.ADMIN_PATH').'/swap_histories/requestVoid/{id}', [AdminSwapHistoriesController::class, 'requestVoid']);
    Route::get(config('crudbooster.ADMIN_PATH').'/swap_histories/getDetails/{id}', [AdminSwapHistoriesController::class, 'getDetails']);

    //CAPSULE SWAP
    Route::post(config('crudbooster.ADMIN_PATH').'/capsule_swap_headers/check-machine', [AdminCapsuleSwapHeadersController::class, 'checkMachine'])->name('check-machine');
    Route::post(config('crudbooster.ADMIN_PATH').'/capsule_swap_headers/save-swap', [AdminCapsuleSwapHeadersController::class, 'saveSwap'])->name('submit_swap');

    //TOKEN SWAP HISTORY
    Route::post('admin/swap_histories/export', [AdminSwapHistoriesController::class, 'exportSwapHistoryData'])->name('swap_histories.export');

    //ITEMS
    Route::get(config('crudbooster.ADMIN_PATH').'/items/upload-items',[AdminItemsController::class, 'importData']);
    Route::post(config('crudbooster.ADMIN_PATH').'/items/upload-items-save',[AdminItemsController::class, 'importPostSave'])->name('upload-item-save');
    Route::get(config('crudbooster.ADMIN_PATH').'/items/upload-items-template',[AdminItemsController::class, 'importItemsTemplate']);
    Route::post(config('crudbooster.ADMIN_PATH').'/items/export', [AdminItemsController::class, 'exportData'])->name('items_export');


    Route::get(config('crudbooster.ADMIN_PATH').'/collect_token/add_collect_token',[AdminCollectTokenController::class, 'AddCollectToken'])->name('AddCollectToken');

    
});