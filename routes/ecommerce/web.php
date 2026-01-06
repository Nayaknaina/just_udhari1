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

// Route::get('/', function () {
//  return view('welcome');
// });



Route::get("/notfound", function () {
 echo "Page Not Found";
});



$branch = app('branch');


Route::get("/{$branch}", "SiteController@index");

Route::get("/{$branch}about", "SiteController@about");

Route::get("/{$branch}contact", "SiteController@contact");

Route::POST("/{$branch}contact", "SiteController@sendenquiry");

//Route::get("/{$branch}shop/{item?}", "SiteController@shop");

Route::get("/{$branch}cateloge", "SiteController@categols");

Route::get("/{$branch}cateloge/gallery/{unique?}","SiteController@cataloggallery");

Route::match(['GET', 'POST'],"/{$branch}shop/{item?}", "SiteController@shop");

Route::get("/{$branch}shopdata","SiteController@shoppage");

Route::get("/{$branch}product/{unique}", "SiteController@product");

Route::get("/{$branch}scheme", "SiteController@scheme");

Route::get("/{$branch}schemerule/{url}", "SiteController@scheme_rules");
//----P-CODE 6-9-2024-------//
Route::get("/{$branch}scheme/{id}", "SiteController@scheme_details")->name('schemes') ;
//----END P-CODE 6-9-2024-------//

Route::get("/{$branch}register", "SiteController@custoreg");

Route::get("/{$branch}sendotp","SiteController@sendotp");

Route::post("/{$branch}register","SiteController@customerregister");


Route::get("/{$branch}login", "SiteController@custologin");

Route::post("/{$branch}login", "SiteController@attemptlogin");


Route::get("/{$branch}forgot/{event?}", "SiteController@custoforget");
Route::post("/{$branch}forgot", "SiteController@proceedforget");

Route::get("/{$branch}privacy-policy", "SiteController@policy");
Route::get("/{$branch}refund-policy", "SiteController@refundpolicy");
Route::get("/{$branch}terms-conditions", "SiteController@terms");
Route::get("/{$branch}desclaimer", "SiteController@desclaimer");
Route::get("/{$branch}shiping-policy", "SiteController@shipingpolicy");
Route::get("/{$branch}account-delete", "SiteController@acdeletepolicy");

Route::get("/{$branch}shop-location", "SiteController@location");


Route::get("/{$branch}addtowishlist/{id}","SiteController@addwishlist");
Route::get("/{$branch}addtokart/{id}","SiteController@addkart");
Route::get("/{$branch}removecart/{id}","SiteController@removefromkart");
Route::get("/{$branch}removewish/{id}","SiteController@removefromwishlist");
Route::get("/{$branch}movetokart/{id}","SiteController@shiftwishlisttokart");
Route::get("/{$branch}soppinglistcount","SiteController@getshoppinglistcount");

Route::match(['GET', 'POST'], "/{$branch}logout","SiteController@logout")->name('logout');
//Auth::routes();

Route::get("/{$branch}schemeenquiry/{shopscheme}","SiteController@schemeenquiry");
Route::post("/{$branch}schemeenquiry","SiteController@sendschemeenquiry");

Route::get('/get-districts', 'SiteController@get_districts');

Route::get("/{$branch}/currentrate",'SiteController@getcurrentrate')->name('shop.rate');
