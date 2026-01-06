<?php

use Illuminate\Support\Facades\Route;

// Route::group(['namespace' => 'Settings', 'middleware' => ['auth']], function () {

Route::group(['middleware' => ['auth']], function () {

	Route::resource('/suppliers', 'SupplierController') ;
	Route::resource('/counters', 'CounterController') ;
	Route::resource('/shopbranches', 'ShopBranchController') ;
	Route::resource('/categories', 'CategoryController') ;    
	Route::resource('/designations', 'DesignationController') ;
	Route::resource('/employees', 'EmployeeController') ;
	Route::resource('/settings', 'SettingsController') ;
	Route::Match(['GET','POST'],'/setting/mpin/{section}/{event?}','SettingsController@newmpin')->name('setting.mpin');
	Route::Match(['get','post'],"/todaysrates","SettingsController@todayrate")->name('currentrates');
	Route::match(['get','post'],'/rate/{delete}/{id}',"SettingsController@todayrate")->name('currentrate.delete');
}) ;
