<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPurchaseOrdersController;
use App\Http\Controllers\AdminSalesOrdersController;
use App\Http\Controllers\AdminUomsController;
use App\Http\Controllers\AdminStoresController;
use App\Http\Controllers\AdminSuppliersController;
use App\Http\Controllers\AdminPoReceivingController;


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

Route::post('/admin/purchase_orders/order-upload',[AdminPurchaseOrdersController::class, 'orderUpload'])->name('purchase-order.upload');
Route::get('/admin/purchase_orders/order-template',[AdminPurchaseOrdersController::class, 'orderTemplate'])->name('purchase-order.template');
Route::get('/admin/purchase_orders/order',[AdminPurchaseOrdersController::class, 'orderView'])->name('purchase-order.view');

Route::post('/admin/sales_orders/order-upload',[AdminSalesOrdersController::class, 'orderUpload'])->name('sales-order.upload');
Route::get('/admin/sales_orders/order-template',[AdminSalesOrdersController::class, 'orderTemplate'])->name('sales-order.template');
Route::get('/admin/sales_orders/order',[AdminSalesOrdersController::class, 'orderView'])->name('sales-order.view');

Route::post('/admin/uoms/uom-upload',[AdminUomsController::class, 'uomUpload'])->name('uom.upload');
Route::get('/admin/uoms/uom-template',[AdminUomsController::class, 'uomTemplate'])->name('uom.template');
Route::get('/admin/uoms/import',[AdminUomsController::class, 'uomView'])->name('uom.view');

Route::post('/admin/stores/store-upload',[AdminStoresController::class, 'storeUpload'])->name('store.upload');
Route::get('/admin/stores/store-template',[AdminStoresController::class, 'storeTemplate'])->name('store.template');
Route::get('/admin/stores/import',[AdminStoresController::class, 'storeView'])->name('store.view');

Route::post('/admin/suppliers/supplier-upload',[AdminSuppliersController::class, 'supplierUpload'])->name('supplier.upload');
Route::get('/admin/suppliers/supplier-template',[AdminSuppliersController::class, 'supplierTemplate'])->name('supplier.template');
Route::get('/admin/suppliers/import',[AdminSuppliersController::class, 'supplierView'])->name('supplier.view');

Route::get('/admin/po/order-received',[AdminPoReceivingController::class, 'orderReceived'])->name('receiving.po-received');
