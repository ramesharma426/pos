<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*login*/
Route::post('/login', [AuthenticationController::class, 'login']);
Route::middleware(['auth:sanctum'])->get('/logout', [AuthenticationController::class, 'logout']);

/*unit*/
Route::middleware(['auth:sanctum'])->get('/units',[UnitController::class, 'index']);
Route::middleware(['auth:sanctum'])->post('/unit/store', [UnitController::class, 'store']);
Route::middleware(['auth:sanctum'])->post('/unit/update', [UnitController::class, 'update']);
Route::middleware(['auth:sanctum'])->delete('/unit/delete/{unit_id}', [UnitController::class, 'destroy']);

/*category*/
Route::middleware(['auth:sanctum'])->get('/categories',[CategoryController::class, 'index']);
Route::middleware(['auth:sanctum'])->post('/category/store', [CategoryController::class, 'store']);
Route::middleware(['auth:sanctum'])->post('/category/update', [CategoryController::class, 'update']);
Route::middleware(['auth:sanctum'])->delete('/category/delete/{category_id}', [CategoryController::class, 'destroy']);

/*order*/
Route::middleware(['auth:sanctum'])->get('/orders',[OrderController::class, 'index']);
Route::middleware(['auth:sanctum'])->post('/order/store', [OrderController::class, 'store']);
Route::middleware(['auth:sanctum'])->post('/order/update', [OrderController::class, 'update']);
Route::middleware(['auth:sanctum'])->delete('/order/delete/{order_id}', [OrderController::class, 'destroy']);

/*product*/
Route::middleware(['auth:sanctum'])->get('/products',[ProductController::class, 'index']);
Route::middleware(['auth:sanctum'])->get('/products/name',[ProductController::class, 'productName']);
Route::middleware(['auth:sanctum'])->post('/product/store', [ProductController::class, 'store']);
Route::middleware(['auth:sanctum'])->post('/product/update', [ProductController::class, 'update']);
//Route::middleware(['auth:sanctum'])->post('/product/stock/update', [ProductController::class, 'updateStock']);
Route::middleware(['auth:sanctum'])->delete('/product/delete/{product_id}', [ProductController::class, 'destroy']);

/*product variant*/
Route::middleware(['auth:sanctum'])->get('/product-variants',[ProductVariantController::class, 'index']);
Route::middleware(['auth:sanctum'])->get('/product-variants/name',[ProductVariantController::class, 'productVariantName']);
Route::middleware(['auth:sanctum'])->post('/product-variant/store', [ProductVariantController::class, 'store']);
Route::middleware(['auth:sanctum'])->post('/product-variant/update', [ProductVariantController::class, 'update']);
Route::middleware(['auth:sanctum'])->delete('/product-variant/delete/{product_variant_id}', [ProductVariantController::class, 'destroy']);

/*payment*/
Route::middleware(['auth:sanctum'])->get('/payments',[PaymentController::class, 'index']);
Route::middleware(['auth:sanctum'])->post('/payment/store', [PaymentController::class, 'store']);
Route::middleware(['auth:sanctum'])->get('/payment/discount-by-date', [PaymentController::class, 'discountByDate']);
Route::middleware(['auth:sanctum'])->get('/payment/reprint/{order_id}', [PaymentController::class, 'reprint']);

/*table*/
Route::middleware(['auth:sanctum'])->get('/tables',[TableController::class, 'index']);
Route::middleware(['auth:sanctum'])->post('/table/store', [TableController::class, 'store']);
Route::middleware(['auth:sanctum'])->post('/table/update', [TableController::class, 'update']);
Route::middleware(['auth:sanctum'])->delete('/table/delete/{table_id}', [TableController::class, 'destroy']);

/*order items*/
Route::middleware(['auth:sanctum'])->post('/order-item/store', [OrderItemController::class, 'store']);
Route::middleware(['auth:sanctum'])->get('/order-item-deliver/update/{orderItem_id}', [OrderItemController::class, 'deliverOrderItem']);
Route::middleware(['auth:sanctum'])->get('/order-items/{order_id}', [OrderItemController::class, 'orderItems']);
Route::middleware(['auth:sanctum'])->get('/order-item-cancel/{orderItem_id}', [OrderItemController::class, 'cancelOrderItem']);

//users
Route::middleware(['auth:sanctum','role:admin'])->get('/users', [UserController::class, 'index']);
Route::middleware(['auth:sanctum','role:admin'])->post('/user/store', [UserController::class, 'store']);
Route::middleware(['auth:sanctum','role:admin'])->post('/user/name-update', [UserController::class, 'nameUpdate']);
Route::middleware(['auth:sanctum','role:admin'])->post('/user/email-update', [UserController::class, 'emailUpdate']);
Route::middleware(['auth:sanctum','role:admin'])->post('/user/password-update', [UserController::class, 'passwordUpdate']);
Route::middleware(['auth:sanctum','role:admin'])->post('/user/role-update', [UserController::class, 'roleUpdate']);
Route::middleware(['auth:sanctum','role:admin'])->delete('/user/delete/{user_id}', [UserController::class, 'destroy']);

/*role*/
Route::middleware(['auth:sanctum'])->get('/roles', [RoleController::class, 'index']);

/*purchase*/
Route::middleware(['auth:sanctum'])->get('/purchases', [PurchaseController::class, 'index']);
Route::middleware(['auth:sanctum'])->post('/purchase/store', [PurchaseController::class, 'store']);

/*sales*/
Route::middleware(['auth:sanctum'])->get('/sales', [SalesController::class, 'index']);
