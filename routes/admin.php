<?php

use Illuminate\Http\Request;
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

    Route::group(['middleware' => ['IsSuperadmin']], function () {

        Route::post('logout', 'Auth\SuperAdminLoginController@logout')->name('logout') ;
        Route::get('/', 'HomeController@index') ;
        Route::get('/dashboard', 'HomeController@index')->name('dashboard')  ;

        //////////////////////////////////////////////////////////////////////////////

        Route::resource('/softwareproducts', 'SoftwareProductController') ;
        Route::resource('/schemes', 'SchemeController') ;
        Route::resource('/roles', 'RoleController') ;
        Route::resource('/permissions', 'PermissionController') ;
        Route::resource('/users', 'UserController') ;
        Route::get('/ecommsetups/{id}', 'UserController@ecommsetups')->name('ecommsetups.index') ;
        Route::post('/ecommsetups/{id}', 'UserController@ecommsetups_update')->name('ecommsetups') ;
        Route::get('/schemes/assign/{id}', 'UserController@schemes')->name('schemes.assign.to') ;
        Route::post('/schemes/assign', 'UserController@scheme_assign')->name('schemes.assign') ;
        Route::resource('/shoprights', 'ShopRightController') ;
        Route::resource('/productcategories', 'CategoryController') ;
        Route::get('/productcategories/add/{id}', 'CategoryController@add')->name('productcategories.add') ;
        Route::resource('/webinformation', 'WebInformationController') ;
        Route::resource('/webpages', 'WebPageController') ;
        Route::resource('/faqs', 'FaqController') ;
        Route::resource('/whatsnew', 'WhatsNewController') ;
		
		//----------Payment Gateway Tamplate-----------------------------------------//
        Route::resource('/paymentgateway', 'PaymentGatewayController') ;
        Route::post("/paymentgateway/assign","PaymentGatewayController@assign")->name("paymentgateway.assign");

    }) ;
