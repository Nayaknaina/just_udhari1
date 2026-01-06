<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::post('global/customer/save','Controller@newcustomerwithcategory')->name('global.customer.save');
    Route::get("global/customers/find",'Controller@searchcustomer')->name("global.customers.search");
	
	//-------------------------To Add Item Category From Any Where----------------------//
    Route::post('global/itemtype/save','Controller@newitemcategory')->name('global.itemtype.save');
	
	//--To Resend the Message From Any Module-------------------------------------------//
    Route::match(['get','post'],'textmessage/resend/{id?}','Controller@resendtextmessage')->name('textmessage.resend');
	
    Route::match(['get','post'],'textmessage/delete/{id?}','Controller@deletetextmessage')->name('textmessage.delete');
});