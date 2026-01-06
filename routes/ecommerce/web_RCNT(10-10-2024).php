<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\Ecommerce\SiteController;

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

// Route::get('/', function () {
//  return view('welcome');
// });



Route::get("/notfound", function () {
 echo "Page Not Found";
});

// $branch = app('branch');

$branch = app('branch') ;

Route::get("/{$branch}", "SiteController@index");

Route::get("/{$branch}about", "SiteController@about");
Route::get("/{$branch}contact", "SiteController@contact");

Route::get("/{$branch}shop/{item?}", "SiteController@shop");

Route::get("/{$branch}product/{unique}", "SiteController@product");

Route::get("/{$branch}wishlist", "SiteController@wishlist");
Route::get("/{$branch}cart", "SiteController@cart");
Route::get("/{$branch}checkout", "SiteController@checkout");

Route::get("/{$branch}scheme", "SiteController@scheme");
Route::get("/{$branch}scheme/{id}", "SiteController@scheme_details")->name('schemes') ;

Route::get("/{$branch}register", "SiteController@custoreg");
Route::get("/{$branch}sendotp","SiteController@sendotp");
Route::post("/{$branch}register","SiteController@customerregister");

Route::get("/{$branch}login", "SiteController@custologin");
Route::post("/{$branch}login", "SiteController@attemptlogin");

Route::get("/{$branch}privacy-policy", "SiteController@policy");
Route::get("/{$branch}terms-conditions", "SiteController@terms");
Route::get("/{$branch}desclaimer", "SiteController@desclaimer");

Route::get("/{$branch}shop-location", "SiteController@location");

//Auth::routes();

//Route::get('/home', [HomeControllerindex::class,"home"])->name('home');
