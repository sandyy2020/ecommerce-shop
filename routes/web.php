<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductSubCategory;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Middleware\AdminAuthenticate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


//Only User Middleware
Route::group(['middleware' => 'admin.guest'], function () {
    Route::get('/admin/login',[AdminLoginController::class,'index'])->name('admin.login');
    Route::post('/admin/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');
});

//Only Admin Middleware
Route::group(['middleware' => 'admin.auth'], function () {
    Route::get('/admin/dashboard',[HomeController::class,'index'])->name('admin.dashboard');
    Route::get('/admin/logout',[HomeController::class,'logout'])->name('admin.logout');    

    //categories routes
    Route::get('/admin/categories/index',[CategoryController::class,'index'])->name('categories.index');
    Route::get('/admin/categories/create',[CategoryController::class,'create'])->name('categories.create');
    Route::post('/admin/categories',[CategoryController::class,'store'])->name('categories.store');
    Route::get('/admin/categories/{category}/edit',[CategoryController::class,'edit'])->name('categories.edit');
    Route::put('/admin/categories/{category}',[CategoryController::class,'update'])->name('categories.update');
    Route::delete('/admin/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    //sub categories routes
    Route::get('/admin/sub-categories/index',[SubCategoryController::class,'index'])->name('sub-categories.index');
    Route::get('/admin/sub-categories/create',[SubCategoryController::class,'create'])->name('sub-categories.create');
    Route::post('/admin/sub-categories',[SubCategoryController::class,'store'])->name('sub-categories.store');
    Route::get('/admin/sub-categories/{subcategory}/edit',[SubCategoryController::class,'edit'])->name('sub-categories.edit');
    Route::put('/admin/sub-categories/{subcategory}',[SubCategoryController::class,'update'])->name('sub-categories.update');
    Route::delete('/admin/sub-categories/{id}',[SubCategoryController::class,'destroy'])->name('sub-categories.destroy');

    //brand routes
    Route::get('/admin/brand/index',[BrandController::class,'index'])->name('brand.index');
    Route::get('/admin/brand/create',[BrandController::class,'create'])->name('brand.create');
    Route::post('/admin/brand',[BrandController::class,'store'])->name('brand.store');
    Route::get('/admin/brand/{brand}/edit',[BrandController::class,'edit'])->name('brand.edit');
    Route::put('/admin/brand/{brand}',[BrandController::class,'update'])->name('brand.update');
    Route::delete('/admin/brand/{id}',[BrandController::class,'destroy'])->name('brand.destroy');

    //product routes
    Route::get('/admin/products/index',[ProductController::class,'index'])->name('products.index');
    Route::get('/admin/products/create',[ProductController::class,'create'])->name('products.create');
    Route::post('/admin/products',[ProductController::class,'store'])->name('products.store');
    Route::get('/admin/products/{brand}/edit',[ProductController::class,'edit'])->name('products.edit');
    Route::put('/admin/products/{brand}',[ProductController::class,'update'])->name('products.update');
    Route::delete('/admin/products/{id}',[ProductController::class,'destroy'])->name('products.destroy');

    //productsubcategory routes used in product table
    Route::get('admin/subcategories', [ProductSubCategory::class, 'getSubcategories'])->name('get.subcategories');

    //temp_images
    Route::post('/admin/upload-temp-image',[TempImagesController::class,'create'])->name('temp-images.create');
    // Route to handle temporary image deletions
    Route::delete('/temp-images/{id}', [TempImagesController::class, 'destroy'])->name('temp-images.delete');


});