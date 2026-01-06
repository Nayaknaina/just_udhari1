<?php

use Illuminate\Support\Facades\Route;

$branch = app('branch');

Route::get("/{$branch}dashboard", "CustomerController@index");

Route::get("/{$branch}profile", "CustomerController@myprofile");
Route::post("/{$branch}profile", "CustomerController@saveprofile");

Route::get("/{$branch}password", "CustomerController@mypassword");
Route::post("/{$branch}password", "CustomerController@changepassword");

Route::get("/{$branch}wishlist", "CustomerController@mywishlist");

Route::get("/{$branch}cart", "CustomerController@mycart");

Route::get("/{$branch}schemes", "CustomerController@myscheme");

Route::post("/{$branch}createorder", "CustomerController@createorder");

Route::match(["GET", "POST"], "/{$branch}checkout/{unique}", "CustomerController@checkout");

Route::post("/{$branch}/orderplace", "CustomerController@orderplace");

Route::get("/{$branch}orders", "CustomerController@allorders");

Route::get("/{$branch}orderdetail", "CustomerController@singleorder");

Route::get("/{$branch}transactions/{param?}", "CustomerController@alltransactions");

Route::get("/{$branch}payresponse/{unique}", "CustomerController@paymentresponse");

Route::get("/{$branch}order", "CustomerController@recentorder");

Route::get("/{$branch}orderdetail/{id}", "CustomerController@orderdetail");

Route::get("/{$branch}schemedetail/{id}", "CustomerController@grouppaydetail");
    
Route::get("/{$branch}txnsdetail/{id}","CustomerController@schemegrouptxns");

//Route::get("/{$branch}emipay/{id}", "CustomerController@schemepay");

Route::match(["GET", "POST"], "/{$branch}emipay", "CustomerController@schemepay");

Route::get("/{$branch}enquiries", "CustomerController@enquiries");

//---------EMI PAY ROUTE-------------------------------------------------//
Route::Post("/{$branch}payemi", "CustomerController@emiorder");


//---EMI Payment Respoonse URL----------------------------------------//
Route::match(['GET', 'POST'],"/{$branch}{gatewayname}/emipayresponse", "CustomerController@emipayresponse");
//---Product ORDER Payment REsponse URL-------------------------------// 
Route::match(['GET', 'POST'],"/{$branch}{gatewayname}/paymentresponse", "CustomerController@paymentresponse");