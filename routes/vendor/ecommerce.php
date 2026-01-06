<?php

Route::middleware(['auth'])->group(function () {

    Route::resource('/ecomproducts', 'EcommProductController') ;
    Route::resource('/ecomproducts/images', 'EcommProductImageController') ;
	
	Route::get("/catalogues/gallery/delete/{catalogeimage}","CatalogeController@deletegallery")->name('gallery.delete');
	
    Route::resource('/catalogues', 'CatalogeController') ;
	
    Route::post('/gallery/save','CatalogeController@savegallery')->name('catalogues.gallery');
	
    Route::resource('/ecomcategories', 'CategoryController') ;
    Route::resource('/ecomstocks', 'StockController') ;
	
	Route::get('/productorders', 'OrderController@products')->name('ecomorders.products') ;
    Route::get('/orderdetail/{id}', 'OrderController@productorderdetail')->name('ecomorders.orderdetail') ;
    Route::get('/ordertxns/{id}', 'OrderController@productordertxns')->name('ecomorders.productordertxns') ;
    Route::get('/schemeorders', 'OrderController@schemes')->name('ecomorders.schemes') ;
    Route::get('/schemetxn/{id}', 'OrderController@schemeordertxns')->name('ecomorders.schemetxns') ;
    Route::get('/cart','OrderController@ecommcart')->name('ecomorders.cart');
	
    Route::get('/transactions/{type}', 'OrderController@alltransactions')->name('ecomorders.txns') ;
	
    Route::get('/ecomdashboard', 'EcommCmsController@index')->name('ecomdashboard') ;

    // Slider  ===========================================================================================

    Route::get('/ecomslider', "EcommCmsController@ecomslider")->name('ecomslider') ;
    Route::get("/ecomslider/loaddata","EcommCmsController@ecomslider")->name('ecomslider.loaddata') ;
    Route::post("/ecomslider","EcommCmsController@saveslider")->name('ecomslider') ;
    Route::get("/ecomslider/editslide/{unique?}","EcommCmsController@edit_slider")->name('ecomslider.editslide') ;
    Route::post("/ecomslider/update/{unique?}","EcommCmsController@updateslider")->name('ecomslider.update') ;
    Route::get("/ecomslider/slidestatus/{unique?}","EcommCmsController@slidestatus")->name('ecomslider.slidestatus');
    Route::get("/ecomslider/orderslider","EcommCmsController@slideorder")->name('ecomslider.orderslider');
    Route::get("/ecomslider/deleteslide/{unique?}","EcommCmsController@deleteslider")->name('ecomslider.deleteslide') ;

    //  =============================================================================================

    Route::post('/homecontent', "EcommCmsController@savecontent") ;

    Route::get('/aboutcontent', "EcommCmsController@about")->name('aboutcontent') ;
    Route::post('/aboutcontent', "EcommCmsController@saveabout")->name('aboutcontent') ;
    Route::get('/aboutcontent/status', "EcommCmsController@aboutstatus")->name('aboutcontent.status') ;

    Route::resource('ecommwebinformations', WebInformationController::class);

    Route::get('/contactinformation', "EcommCmsController@contact")->name('contactinformation');
    Route::get('/contactinformation/data', "EcommCmsController@contactdata")->name('contactinformation.data');
    Route::post('/contactinformation', "EcommCmsController@savecontact")->name('contactinformation');

    Route::get('/contactinformation/status', "EcommCmsController@contactstatus")->name('contactinformation.status') ;
    Route::get('/contactinformation/email/status', "EcommCmsController@emailvisible")->name('contactinformation.email.status') ;
    Route::get('/contactinformation/fone/status', "EcommCmsController@fonevisible")->name('contactinformation.fone.status') ;

    Route::get('/footercontent', "EcommCmsController@footer")->name('footercontent');
    Route::get('/footercontent/data', "EcommCmsController@data")->name('footercontent.data');
    Route::post('/footercontent', "EcommCmsController@savefooter")->name('footercontent');
    Route::get('/footercontent/status', "EcommCmsController@footerstatus")->name('footercontent.status');

    Route::get("/sociallink", "EcommCmsController@social")->name('sociallink');
    Route::get("/sociallink/data", "EcommCmsController@socialdata")->name('sociallink.data');
    Route::post("/sociallink", "EcommCmsController@savesocial")->name('sociallink');
    Route::get("/sociallink/status","EcommCmsController@socialstatus")->name('sociallink.status');

    Route::get('/termsandconditions', "EcommCmsController@terms")->name('termsandconditions');
    Route::get('/termsandconditions/termdata', "EcommCmsController@termdata")->name('termsandconditions.termdata');
    Route::post('/termsandconditions', "EcommCmsController@saveterm")->name('termsandconditions');
    Route::get("/termsandconditions/status","EcommCmsController@termstatus")->name('termsandconditions.status');

    Route::get('/privacypolicy', "EcommCmsController@policy")->name('privacypolicy');
    Route::get('/privacypolicy/policydata', "EcommCmsController@policydata")->name('privacypolicy.policydata');
    Route::post('/privacypolicy', "EcommCmsController@savepolicy")->name('privacypolicy');
    Route::get("/privacypolicy/status","EcommCmsController@privacystatus")->name('privacypolicy.status');
	
	
	Route::get('/refundpolicy', "EcommCmsController@refundpolicy")->name('refundpolicy');
    Route::get('/refundpolicy/policydata', "EcommCmsController@refunddata")->name('refundpolicy.policydata');
    Route::post('/refundpolicy', "EcommCmsController@saverefundpolicy")->name('refundpolicy');
    Route::get("/refundpolicy/status","EcommCmsController@refundstatus")->name('refundpolicy.status');

    Route::get('/desclaimer', "EcommCmsController@desclaimer")->name('desclaimer');
    Route::get('/desclaimer/desclaimerdata', "EcommCmsController@desclaimerdata")->name('desclaimer.desclaimerdata');
    Route::post('/desclaimer', "EcommCmsController@savedesclaimer")->name('desclaimer');
    Route::get("/desclaimer/status","EcommCmsController@desclaimerstatus")->name('desclaimer.status');

	Route::get('/shiping', "EcommCmsController@shiping")->name('shiping');
    Route::get('/shiping/shipingdata', "EcommCmsController@shipingdata")->name('desclaimer.desclaimerdata');
    Route::post('/shiping', "EcommCmsController@saveshiping")->name('shiping');
    Route::get("/shiping/status","EcommCmsController@shipingstatus")->name('shiping.status');
	
    Route::get('/deletionpolicy', "EcommCmsController@acdelete")->name('acdelete');
    Route::get('/deletionpolicy/policydata', "EcommCmsController@policygdata")->name('acdelete.policydata');
    Route::post('/deletionpolicy', "EcommCmsController@savesacdelete")->name('acdelete');
    Route::get("/deletionpolicy/status","EcommCmsController@acdeletestatus")->name('acdelete.status');

}) ;
