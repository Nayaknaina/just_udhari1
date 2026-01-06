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

Route::post("/{$branch}createorder","CustomerController@createorder");

Route::match(["GET","POST"],"/{$branch}checkout/{unique}", "CustomerController@checkout");

Route::post("/{$branch}/orderplace","CustomerController@orderplace");

Route::get("/{$branch}orders", "CustomerController@allorders");

Route::get("/{$branch}orderdetail", "CustomerController@singleorder");

Route::get("/{$branch}transactions", "CustomerController@alltransactions");

Route::get("/{$branch}payresponse/{unique}","CustomerController@paymentresponse");

Route::get("/{$branch}order","CustomerController@recentorder");

Route::get("/{$branch}orderdetail/{id}", "CustomerController@orderdetail");

// Route::group(['middleware' => ['customer']], function () {
//     $branch = app('branch');
// });

?>