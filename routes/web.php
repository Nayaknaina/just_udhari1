<?php

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
// Route::middleware('DomainRouting')->group(function () {

    Route::get('/', 'HomeController@index') ;

    Route::get('/home', 'HomeController@index') ;
    Route::get('/products/{url}', 'HomeController@products') ;
    Route::get('/whats-new', 'HomeController@whatsnew') ;
    Route::get('/software-tutorial', 'HomeController@softwaretutorial') ;
    Route::get('/faq', 'HomeController@faq') ;
    Route::get('/contact', 'HomeController@contact') ;
    Route::get('/login', 'Auth\LoginController@index')->name('login') ;
    Route::post('/login', 'Auth\LoginController@login')->name('login') ;
	
    Route::get('/forget-password/{event?}','Auth\LoginController@passforgot');
    Route::post('/forget-password','Auth\LoginController@processpassforgot');
	
    Route::get('/register', 'Auth\RegisterController@index')->name('register') ;
    Route::post('/register', 'Auth\RegisterController@register')->name('register') ;
    Route::get('/verification', 'Auth\VerificationController@index')->name('verification') ;
    Route::post('/verification', 'Auth\VerificationController@verify')->name('verification') ;
    Route::post('/resend_otp', 'Auth\VerificationController@resend_otp')->name('resend_otp') ;
    Route::get('/get-districts', 'LocationController@get_districts');
	Route::get('/policy/{param?}','HomeController@information')->name('policy');
	Route::get('/request/delete/account','HomeController@accountdeleterequest')->name('accountdeleterequest');

    Route::post('/contact/store',  'ContactController@store')->name('contact.store');

   
    // Admin Login
    Route::get('/super_login', 'Auth\AdminLoginController@index')->name('super_login') ;
    Route::post('/super_login', 'Auth\AdminLoginController@login')->name('super_login') ;

// });

// include 'admin.php' ;

