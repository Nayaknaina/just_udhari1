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

Route::post('logout', 'Auth\LoginController@logout')->name('vendors.logout') ;

Route::middleware(['auth'])->group(function () {




       /*--------------notification root----------------*/

        Route::get('notifications', function () {

            $notifications = auth()->user()
                ->notifications()
                ->latest()
                ->get();

            return response()->json([
                'data' => $notifications
            ]);

        })->name('notifications');


    Route::get('notifications/unread-count', function () {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count()
        ]);
    })->name('notifications.unread');

    Route::post('notifications/mark-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['status' => true]);
    })->name('notifications.read');

    Route::post('/notifications/clear', function () {
        auth()->user()->notifications()->whereNotNull('read_at')->delete();
        return response()->json(['status' => true]);
    })->name('notifications.clear');

    Route::post('notifications/delete', function (Illuminate\Http\Request $r) {

        auth()->user()
            ->notifications()
            ->where('id', $r->id)
            ->delete();

        return response()->json(['success' => true]);
    })->name('notifications.delete');

    Route::post('notifications/read-single', function (Illuminate\Http\Request $r) {
    auth()->user()
        ->notifications()
        ->where('id', $r->id)
        ->update(['read_at' => now()]);

    return response()->json(['ok' => true]);
    })->name('notifications.read.single');

    Route::post('notifications/read-all', function () {
    auth()->user()
        ->unreadNotifications
        ->markAsRead();

    return response()->json(['ok' => true]);
    })->name('notifications.read.all');






    /*--------------end notification root----------------*/
	
	
	//----------------- ROUTE FOR BUSSINESS DAY-BOOK THE NEW-----------------//
        Route::get('shop/daybook/initiat',"ShopDayBook@feed")->name('shop.daybook.feed');
        Route::get('shop/daybook/detail/{date?}',"ShopDayBook@detailview")->name('shop.detail');
        Route::get('shop/daybook',"ShopDayBook@list")->name('shop.daybook');

        Route::get('shop/daybook/sources/{date?}',"ShopDayBook@getsourcesummerydata")->name('shop.source.summery');
        Route::get('shop/daybook/summery/{target?}',"ShopDayBook@getsummerydata")->name('shop.summery.target');
        
        Route::get('shop/daybook/export',"ShopDayBook@exportdaysheet")->name('shop.detail.export');
        

	//----------------END ROUTE FOR BUSSINESS DAY-BOOK THE NEW-----------------//
	

    Route::post('permission-enquiry', 'CategoryController@add')->name('permission-enquiry') ;

    Route::get('/', 'HomeController@index')->name('vendors') ;
    Route::get('/home', 'HomeController@index')->name('vendors.home') ;

	
    Route::get("customers/find",'UdharController@searchcustomer')->name("customers.search");
	
	
	
    //  Schemes =============================================================================================|

        Route::resource('/shopscheme', 'ShopSchemeController') ;
        
        Route::post('shopschemes/profilefoto','ShopSchemeController@addfoto')->name('shopscheme.profilefoto');
        
        Route::get("/shopschemes/due","ShopSchemeController@dueamount")->name("shopscheme.due");

		/*Route::get("/shopschemes/due/list/pdf","ShopSchemeController@dueamountlistpdf")->name("shopscheme.due.pdflist");*/
		
		Route::get("/shopschemes/due/export/{export?}","ShopSchemeController@dueamountlistexport")->name("shopscheme.due.export");

		Route::get("/shopschemes/due/list","ShopSchemeController@dueamountlist")->name("shopscheme.due.list");

        Route::get("/shopschemes/enrollgroup/{scheme}","ShopSchemeController@groupcustomer")->name("shopscheme.enrollgroup");

		Route::get("/shopschemes/enrollscheme/{grp}","ShopSchemeController@schemecustomer")->name("shopscheme.enrollscheme");

		Route::get("/shopschemes/daybooksummery","ShopSchemeController@daybooksummery")->name('shopscheme.daybooksummery');
        Route::get("/shopschemes/daybook","ShopSchemeController@daybook")->name('shopscheme.daybook');

        Route::get("/shopschemes/pay","ShopSchemeController@manualpay")->name("shopscheme.pay");
        Route::get("/shopschemes/emipay/{id}","ShopSchemeController@custoemipay")->name("shopscheme.emipay");
        Route::post("/shopschemes/emipay/{enrollcusto}","ShopSchemeController@paycustoemi")->name("shopscheme.emipay");
		
        Route::resource("shopschemes/enrollcustomer","Schemes\EnrollCustomerController") ;
		Route::get("enrolls/withdraw/{enroll}","Schemes\EnrollCustomerController@markwithdraw")->name('enrolls.withdraw');
		
        Route::resource("shopschemes/group","Schemes\SchemeGroupController") ;
        Route::get("shopschemes/getgroup","ShopSchemeController@schemegroup")->name('shopschemes.getgroup') ;
        Route::post("shopschemes/grouplist/{id}",'ShopSchemeController@groupdetail')->name('shopschemes.grouplist');
        Route::post("shopschemes/getbonus",'ShopSchemeController@getbonus')->name('shopschemes.getbonus');

        Route::post("shopschemes/enrollcustomer/customerplus","Schemes\EnrollCustomerController@addcustomer")->name('enrollcustomer/newcustomer');
        
        //Route::get("shopschemes/emipart/{id}/{count}",'ShopSchemeController@emipartform');

        //Route::post("shopschemes/emipart/{id}",'ShopSchemeController@emipartpay');

        Route::get('shopschemes/emiedit/{id}','ShopSchemeController@editemi')->name('shopscheme.emi.edit');
        Route::post('shopschemes/emiedit','ShopSchemeController@emiupdate')->name('shopscheme.emi.update');
        
        
        Route::get('shopschemes/emidelete/{id}','ShopSchemeController@deleteemi')->name('shopscheme.emi.delete');

        Route::post("shopschemes/schemebonus/{id}",'ShopSchemeController@addbonusandclose')->name("shopscheme.emi.bonus");
        Route::get("shopschemes/schemeunlock/{id}",'ShopSchemeController@enrollunlock')->name("shopscheme.emi.unlock");


        Route::post("shopschemes/mpincheck",'ShopSchemeController@mpinblock')->name('shopschemes.mpincheck');

        Route::get('shopschemes/schemeenquiry', 'ShopSchemeController@enquiries')->name('shopschemes.enquiries') ;
        Route::get('shopschemes/schemeenquiry/mark/{id}/{mark}', 'ShopSchemeController@enquirystatusmark')->name('shopschemes.enquiry.mark') ;
        
		
		Route::get('shopschemes/smsrecords/{section?}',"TextmsgController@history")->name('shopschemes.txnmsgrecord');

		
		//-----------------------ANJUMAN SCHEME ROUTES--------------------------------------//
		
		Route::get("/anjuman","AnjumanSchemeController@index")->name('anjuman.dashboard');
		Route::get("/anjuman/monthdue/{id?}","AnjumanSchemeController@monthdue")->name('anjuman.due');

        Route::get("/anjuman/scheme","AnjumanSchemeController@newscheme")->name('anjuman.scheme');
        Route::post("/anjuman/scheme","AnjumanSchemeController@savescheme")->name('anjuman.scheme');
		
		Route::match(['get','post'],'/anjuman/scheme/edit/{scheme}',"AnjumanSchemeController@editschemedata")->name('anjuman.scheme.edit');
        Route::post('/anjuman/scheme/change/{scheme?}',"AnjumanSchemeController@updatescheme")->name('anjuman.scheme.change');
        Route::match(['get','post'],'/anjuman/scheme/delete/{scheme?}',"AnjumanSchemeController@deletescheme")->name('anjuman.scheme.delete');

        Route::get("/anjuman/enroll/{id}","AnjumanSchemeController@newenroll")->name('anjuman.enroll');
        Route::post("/anjuman/enroll/{id?}","AnjumanSchemeController@saveenroll")->name('anjuman.enroll');

		Route::match(['get','post'],'/anjuman/enroll/edit/{enroll}',"AnjumanSchemeController@editenrolldata")->name('anjuman.schemeenroll.edit');
        Route::post('/anjuman/enroll/change/{enroll?}',"AnjumanSchemeController@updateenroll")->name('anjuman.schemeenroll.change');
        Route::match(['get','post'],'/anjuman/enroll/delete/{enroll?}',"AnjumanSchemeController@deleteenroll")->name('anjuman.schemeenroll.delete');

        Route::get("/anjuman/payment/{id}/{custo?}","AnjumanSchemeController@newpayment")->name('anjuman.payment');
        Route::get("/anjuman/findenroll/{key?}","AnjumanSchemeController@findenrolledcustomer")->name('anjuman.find');
        Route::post("/anjuman/payment/{id?}","AnjumanSchemeController@savepayment")->name('anjuman.payment');
		
		Route::get("/anjuman/printtxn/{scheme_txn?}","AnjumanSchemeController@printtxn")->name('anjuman.txn.print');
		
		
        Route::match(['get','post'],'/anjuman/txn/edit/{txn?}',"AnjumanSchemeController@edittxndata")->name('anjuman.payment.edit');
        Route::post('/anjuman/txn/change/{txn?}',"AnjumanSchemeController@updatetxn")->name('anjuman.payment.change');
        Route::match(['get','post'],'/anjuman/txn/delete/{txn?}',"AnjumanSchemeController@deletetxn")->name('anjuman.payment.delete');
		
        Route::get("/anjuman/schemes","AnjumanSchemeController@schemes")->name('anjuman.all.scheme');
        Route::get("/anjuman/newscheme","AnjumanSchemeController@newschemes")->name('anjuman.new.scheme');
        Route::post("/anjuman/newscheme","AnjumanSchemeController@schemesave")->name('anjuman.new.scheme.save');

        Route::get('/anjuman/changescheme/{id}',"AnjumanSchemeController@editscheme",)->name('anjuman.edit.scheme');
        Route::post('/anjuman/changescheme/{id?}',"AnjumanSchemeController@updatescheme",)->name('anjuman.update.scheme');
        Route::delete('/anjuman/deletescheme/{anjumanScheme}',"AnjumanSchemeController@deletescheme",)->name('anjuman.delete.scheme');
		
		
		Route::get("/anjuman/enrolls","AnjumanSchemeController@enrollments")->name('anjuman.all.enroll');
        Route::get("/anjuman/newenroll","AnjumanSchemeController@enroll")->name('anjuman.new.enroll');
        Route::post("/anjuman/newenroll","AnjumanSchemeController@enrollsave")->name('anjuman.save.enroll.save');

        Route::get('/anjuman/changeenroll/{id}',"AnjumanSchemeController@editenroll",)->name('anjuman.edit.enroll');
        Route::post('/anjuman/changeenroll/{id?}',"AnjumanSchemeController@updateenroll",)->name('anjuman.update.enroll');
        Route::delete('/anjuman/deleteenroll/{anjumanschemeenroll}',"AnjumanSchemeController@deleteenroll",)->name('anjuman.delete.enroll');


		
        Route::get("/anjuman/txns","AnjumanSchemeController@transactions")->name('anjuman.all.txns');
        Route::get("/anjuman/newtxn/{id}","AnjumanSchemeController@newtxn")->name('anjuman.new.txns');
        Route::post("/anjuman/newtxn/{id?}","AnjumanSchemeController@txnsave")->name('anjuman.new.txns.save');
		
    // ======================================================================================================|
		Route::get("purchases/forms","PurchaseController@getform")->name("purchases.forms");
        Route::get("purchases/associated","PurchaseController@moreelement")->name("purchases.associated");
		Route::match(['get','post'],"/purchases/delete/{id}",'PurchaseController@deletebill')->name('purchases.delete');
        Route::resource('purchases', 'PurchaseController');
		
		Route::get('purchases/txns/{bill}', 'PurchaseController@billtransaction')->name("purchases.txns");

/*
*
*ROUTE FOR NEW SELL BILLS
*
*/

//----------------ROUTE FOR SELL THE NEW-----------------//
Route::match(['get','post'],'billing/{type}/create',"BillController@newbill")->name('billing');  
Route::match(['get','post'],'billing/{type}/edit/{number}',"BillController@editbill")->name('billing.edit');
Route::post('billing/{type}/update/{number}',"BillController@updatebill")->name('billing.update');
Route::match(['get','post'],'billing/{type}/delete/{number}/{option}',"BillController@deletebill")->name('billing.delete');

Route::get('billing/find/stock','BillController@findstock')->name('billing.find.stock');
Route::get('billing/option/{option}/{customer?}',"BillController@getoption")->name('billing.operation.option');

Route::get('billing/{type}/view/{number}/{print?}',"BillController@billpreview")->name('billing.view');
Route::get('billing/{type}/all',"BillController@allbill")->name('billing.all');
//----------------END ROUTE FOR SELL THE NEW-----------------//

/*
*
*END => ROUTE FOR NEW SELL BILLS
*
*/
/*
*
*ROUTE FOR BILL SETTING(TANPLATE & FORMATE)
*
*/
Route::get('/billing/{id}/download', "BillController@downloadPDF")->name('bill.download');
Route::get('/billing/{id}/view', "BillController@viewPDF")->name('bill.pdfview');
// Add this route in your vendor.php routes file
Route::match(['get','post'], 'billing/settings', "BillController@billSettings")->name('bill.settings');
Route::post('/settings/remove-logo', "BillController@removeLogo") ->name('bill.settings.remove-logo');

Route::post('/settings/remove-signature', "BillController@removeSignature")->name('bill.settings.remove-signature');

Route::post('/settings/remove-watermark', "BillController@removeWatermark") ->name('bill.settings.remove-watermark');

/*
*
*end => ROUTE FOR BILL SETTING(TANPLATE & FORMATE)
*
*/
		//----------------Route For Sell Bills ----------------//	 
		Route::get("/sells/customers",'SellController@customerlist')->name('sells.customer');
        Route::get("/sells/items",'SellController@itemlist')->name('sells.item');
		Route::get("/sells/info",'SellController@detail')->name('sells.info');
		Route::get("/sells/preview/{id}",'SellController@printpreview')->name('sells.preview');
		 Route::match(['get','post'],"/sells/delete/{id}",'SellController@stockreturn')->name('sells.delete');
        Route::resource('sells', 'SellController');
		 
		
        //---------------Route For Udhar------------------------------------//
        //---------------Route For Udhar------------------------------------//
        Route::get('udhar/transactions/{id}',"UdharController@custotxns")->name('udhar.txns');
        Route::get('udhar/ledger/{action?}',"UdharController@summeryledger")->name('udhar.ledger');
        Route::post('udhar/dropnote',"UdharController@savenote")->name('udhar.note.drop');
        Route::post('udhar/send',"UdharController@sendsms")->name('udhar.send');
		Route::delete('udhar/list/delete/{txn}',"UdharController@removetxn")->name('udhar.list.remove');
		
        Route::resource('udhar','UdharController');
        
		Route::get('udhar/txn/print/{size}/{ac}',"UdharController@transactionprint")->name('udhar.txn.print');
		//Route::get('customer/udhardata/{id}',"UdharController@getcustomerudhar")->name('customer.udhardata');
        Route::get('customer/udhardata/{id}',"UdharController@getcustomerudhar");
		 
		 
/*
*
* ROUTE FOR NEW STOCK SYSTEM
*
*/

//----------------ROUTE FOR STOCK THE NEW-----------------//

Route::get("stock/new/home/{stock?}/{jewellery?}","StockController@newdashboard")->name('stock.new.dashboard');
Route::post("stock/new/home/thumbnail","StockController@savethumbnail")->name('stock.new.dashboard.thumbnail');

Route::get('stock/new/items',"StockController@allitemgroups")->name('stock.new.item');

Route::match(['get','post'],'stock/edit/{itemgroup?}/{id?}',"StockController@edititemgroup")->name('stock.edit.itemgroup');
Route::match(['get','post'],'stock/delete/{itemgroup}/{id}',"StockController@deleteitemgroup")->name('stock.delete.itemgroup');

Route::match(['get','post'],'stock/new/item/{group?}','StockController@createitem')->name('stock.create.item');

Route::match(['get','post'],'stock/new/create',"StockController@newstockcreate")->name('stock.new.create');
Route::get('stock/find/item',"StockController@finditems")->name('stock.find.item');
Route::get("stock/find/tagnumber/{item?}","StockController@maxtagnumber")->name('stock.find.tagnumber');

Route::get('stock/add/stock/{item?}','StockController@newaddstockform')->name('stock.create.item.form');
Route::get('stock/get/tag',"StockController@getmaxtagnumber")->name('stock.item.tag');

Route::get('stock/new/recent/{stock?}/{num?}/{print?}',"StockController@newrecentstockview")->name('stock.new.recent');

Route::get('stock/new/groupinventory','StockController@groupinventory')->name('stock.new.groupinventory');
Route::get('stock/new/groupinventory/export/{type?}','StockController@groupinventoryexport')->name('stock.new.groupinventory.export');


Route::get('stock/new/inventory/item/{code}','StockController@iteminventory')->name('stock.new.inventory.item');
Route::get('stock/new/inventory/item/export/{code}/{export}','StockController@iteminventoryexport')->name('stock.new.inventory.item.export');

Route::get('stock/new/inventory','StockController@inventory')->name('stock.new.inventory');
Route::get('stock/new/inventory/export/{type?}','StockController@inventoryexport')->name('stock.new.inventory.export');

Route::get('stock/new/inventory/sample/csv',"StockController@csvsample")->name('stock.new.csv.sample');
Route::match(['get','post'],'stock/new/inventory/import','StockController@importstock')->name('stock.new.inventory.import');
Route::get('stock/new/inventory/import/prev/{stock}/{entry}','StockController@importpreview')->name('stock.new.inventory.import.preview');

Route::match(['get','post'],'stock/new/edit','StockController@editstock')->name('stock.new.edit');
Route::match(['get','post'],'stock/new/delete','StockController@deletestock')->name('stock.new.delete');
//----------------END ROUTE FOR STOCK THE NEW-----------------//

/*
*
* ROUTE FOR NEW STOCK SYSTEM
*
*/
		 
		 //----------------Route For Stocks ----------------//
		Route::get("stocks/forms","StockController@getform")->name("stocks.forms");
		Route::get('/stocks/counter', 'StockController@counter')->name('stocks.counter') ;
        Route::get('/stock/counters', 'StockController@counters')->name('stock.counters') ;
        Route::get('/stock/boxes', 'StockController@boxes')->name('stock.boxes') ;
		
		Route::get('stocks/home/{stone?}', 'StockController@dashboard')->name('stocks.home') ;
        Route::post('/stocks/place', 'StockController@counterplace')->name('stocks.place') ;
        Route::get("stocks/associated","StockController@moreelement")->name("stocks.associated");
		
		Route::get('stocks/tagprint',"StockController@stocktagpage")->name('stocks.printtag');
		
        Route::resource('/stocks', 'StockController') ;
		
		//----------------Route For Stocks Counter----------------//
        Route::resource('stockcounters', 'StockCounterController') ;
		 //----------------Route For Just Bill----------------//
        Route::get("/bills/customers",'JustBillController@findcustomer')->name('bills.customer');
        Route::get("/bills/preview/{id}",'JustBillController@printpreview')->name('bills.preview');
		
        Route::resource('bills', 'JustBillController'); 
		
		 //----------------Route For Bank/Bill Gst Info Bill----------------//
        Route::resource('banking','BankingController');
        Route::get("/banking/status/{id}",'BankingController@status');
        Route::get("/banking/delete/{id}","BankingController@destroy");

        Route::post('/banking/savehsf','BankingController@storehsf')->name('banking.save');
        Route::get('/banking/edithsn/{id}','BankingController@hsngstedit')->name('banking.edithsn');
        Route::post('/banking/hsnupdate/{id}','BankingController@hsngstupdate');
        Route::get('/banking/hsnstatus/{id}','BankingController@hsngststatus');
        Route::get("/banking/deletehsn/{id}","BankingController@hsngstdelete");
		
		
		
        //----------------Route For TextMessageApi ----------------//
        Route::resource('textmsgeapi', 'TextmsgController');
		//----------------END : Route For TextMessageApi ----------------//
		
		//----------------Route For GST Report ----------------//
        Route::get("/gst/report","GstController@index")->name("gst.report");
        Route::get("/gst/info/{section}/{type}/{id}","GstController@detail")->name("gst.info");
        Route::get("/gst/summery","GstController@summery")->name("gst.summery");
        //----------------END : Route For GST Report ----------------//
		
		
        Route::get('/comingsoon', 'HomeController@comingsoon')->name('comingsoon') ;
		
		
        Route::post('customer/add/new',"CustomerController@newcustomer")->name('customers.save.new');
        Route::resource('customers', 'CustomerController') ;

    // Subscription =========================================================================================|

        Route::get('/subscriptions', 'HomeController@subscription_timer')->name('subscriptions') ;

    // END Subscription =====================================================================================|

    // Password Check before Edit============================================================================|

        Route::get('/check-password', 'CheckPasswordController@showPasswordForm')->name('check.password') ;
        Route::post('/check-password', 'CheckPasswordController@confirmPassword')->name('check.password') ;

    // End Password Check before Edit =======================================================================|
    //--------Upload Vendor Profile Foto------------------------------//
    Route::post('settings/uploadfoto',"Settings\SettingsController@addfoto")->name('setting.profilephoto');
    //--------Upload Vendor Profile Foto------------------------------//
	//--------------PaymentGateway Route-------------------------------------------//
    Route::resource("/mygateway","PaymentGatewayController");
	
	
    //---------------GIRVI Route--------------------------------------//
	
	Route::match(['get','post'],'/girvi/{section?}/{page?}/{id?}',function(){
        return view('vendors.comingsoon');
    });
	
	Route::get('/girvi/transactions/{girvicustomer}','GirviController@custotransactions')->name('girvi.custotxns');

    Route::get('/girvi/ladgerbook','GirviController@index')->name('girvi.ladgerbook');

    Route::get('/girvi/gritvilist','GirviController@girvilist')->name('girvi.grirvilist');

	
    Route::get('/girvi/cats','GirviController@create')->name('girvi.cats');
    Route::get('/girvi/custo/{id?}','GirviController@create')->name('girvi.custo');
    Route::get('/girvi/item/return/{item?}','GirviController@returngirvi')->name('girvi.return');
    Route::get('/girvi/all','GirviController@alllist')->name('girvi.list');
    Route::get('girvi/batch/{batch?}','GirviController@getsinglebatch')->name("girvi.batch");
    Route::get('girvi/operation/{section?}/{id?}','GirviController@optionforms')->name('girvi.operation');
    Route::post('girvi/operation/{section?}','GirviController@optionoperation')->name('girvi.operation');


    Route::resource('girvi','GirviController');  
/*
*
*NEW IDTAG ROUTE
*
*/
//---------------NEW IDTAGS Route--------------------------------------//
Route::get('idtags/newpages/scanestock','IdtagController@scanematch')->name('stock.idtags.scane');
Route::get('idtags/newpages/generatetag','IdtagController@generateprint')->name('stock.idtags.generate');
//---------------END NEW IDTAGS Route--------------------------------------//
 /*
*
*END=>NEW IDTAG ROUTE
*
*/
	//---------------IDTAGS Route--------------------------------------//
	Route::get('/idtags/getstock','NewIdtagController@singlestock')->name('idtags.getitem');
    Route::get('/idtags/scanestock','NewIdtagController@scanematch')->name('idtags.match');
    Route::get('/idtags/generatetag','NewIdtagController@generateprint')->name('idtags.generate');
	
    Route::get('/idtags/stock','IdtagController@getstock')->name('idtags.stock');
    Route::get('/idtags/scane','IdtagController@scannedstock')->name('idtags.scane');
    Route::get('/idtags/preview/{code?}','IdtagController@printtag')->name('idtags.preview');
	
    Route::get('/idtags/size/setup','IdtagController@sizesetup')->name('idtags.sizesetup');
    Route::get('idtags/print/tags','IdtagController@printpage')->name('idtags.printtag');
    Route::resource('/idtags','IdtagController');
	
	//---------------JEWELLERY REPAIR  Route--------------------------------------//
    Route::get('jewellery/repair/{page?}',function(){
        return view('vendors.comingsoon');
    })->name('jewellery.repair');
    //---------------END REPAIR  Route--------------------------------------//

    //---------------JEWELLERY EXCHANGE  Route--------------------------------------//
    Route::get('jewellery/exchange/{page?}',function(){
        return view('vendors.comingsoon');
    })->name('jewellery.exchange');
    //---------------END EXCHANGE  Route--------------------------------------//

    //---------------HUMAN RESOURCE  Route--------------------------------------//
    Route::get('human/resource/{page?}',function(){
        return view('vendors.comingsoon');
    })->name('human.resource');
    //---------------HUMAN RESOURCE Route--------------------------------------//
	
	 //------------OUTSOURCE DIRECT DESIGN----------------------------//
    
    
    /*Route::get('girvi', function () {
        return view('vendors.outsource.girvi.index');
    })->name('vendor.girvi.index');

    Route::get('girvi/detail', function () {
        return view('vendors.outsource.girvi.viewdetail');
    })->name('vendor.girvi.viewdetail');*/

    Route::get('girvi/repair', function () {
        return view('vendors.outsource.girvi.repair');
    })->name('vendor.girvi.repair');

    Route::get('girvi/first', function () {
        return view('vendors.outsource.girvi.first');
    })->name('vendor.girvi.first');

    Route::get('girvi/ledger', function () {
        return view('vendors.outsource.girvi.ledgerbook');
    })->name('vendor.girvi.ledger');

    Route::get('girvi/list', function () {
        return view('vendors.outsource.girvi.list');
    })->name('vendor.girvi.list');

    Route::get('rfid', function () {
        return view('vendors.outsource.rfid.index');
    })->name('vendor.rfid.index');

    Route::get('income', function () {
        return view('vendors.outsource.rfid.income-expense');
    })->name('vendor.rfid.income');
    Route::get('list', function () {
        return view('vendors.outsource.rfid.list');
    })->name('vendor.rfid.list');
    Route::get('index', function () {
        return view('vendors.outsource.hrm.index');
    })->name('vendor.hrm.index');

    Route::get('view', function () {
        return view('vendors.outsource.hrm.view_profile');
    })->name('vendor.hrm.view');

    Route::get('add', function () {
        return view('vendors.outsource.hrm.add_employee');
    })->name('vendor.hrm.add');

    Route::get('attendance', function () {
        return view('vendors.outsource.hrm.attendance');
    })->name('vendor.hrm.attendance');


    Route::get('exchange', function () {
        return view('vendors.outsource.exchange.index');
    })->name('vendor.exchange.index');

    Route::get('exchange-list', function () {
        return view('vendors.outsource.exchange.list');
    })->name('vendor.exchange.list');

    Route::get('sample-reciept', function () {
        return view('vendors.outsource.exchange.reciept');
    })->name('vendor.exchange.reciept');

    // --------buy selll-----------
    Route::get('buy-sell', function () {
        return view('vendors.outsource.buy-sell.index');
    })->name('vendor.buy-sell.index');

    Route::get('buy-sell-list', function () {
        return view('vendors.outsource.buy-sell.list');
    })->name('vendor.buy-sell.list');

    Route::get('buy-sell-reciept', function () {
        return view('vendors.outsource.buy-sell.reciept');
    })->name('vendor.buy-sell.reciept');

    /*Route::get('vendor/rates/current', function () {
    return app(HomeController::class)->getRatesAjax(request());
    })->name('vendor.rates.current');*/

    Route::get('vendor/rates/current', "HomeController@getRatesAjax")->name('vendor.rates.current');
	
});
