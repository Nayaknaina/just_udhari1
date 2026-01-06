
<div id="compact_submenuSidebar" class="submenu-sidebar  {{ @$ecomorders }}">
	<button  class="btn btn-sm btn-outline-danger" id="side_close" style="display:none;position:absolute;right:0;border-radius:50%;padding: 1px 5px !important;" >&cross;</button>
	<div class="submenu" id="sample-pages">
            <div class="menu-title">
                <h3>Sample Pages</h3>
            </div>
            <ul class="submenu-list" data-parent-element="#sample-pages">

                <li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#rfidtags" aria-expanded="false">
                        <div> <i class="far fa-circle nav-icon "></i> RFID/TAGS </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="rfidtags" class="collapse " data-parent="#compact_submenuSidebar">
                        <li >
                            <a href="{{ route('vendor.rfid.index') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                Tally/Assign
                            </a>
                        </li>
                        
                    </ul>
                </li>

                <li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#newgirvi" aria-expanded="false">
                        <div> <i class="far fa-circle nav-icon "></i> Girvi </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="newgirvi" class="collapse " data-parent="#compact_submenuSidebar">
                        <li >
                            <a href="{{ route('vendor.girvi.first') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                New (DUMMY)
                            </a>
                        </li>
                        {{--<li >
                            <a href="{{ route('vendor.girvi.index') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                New (ONE)
                            </a>
                        </li>--}}

                        {{--<li >
                            <a href="{{ route('vendor.girvi.viewdetail') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                New (TWO)
                            </a>
                        </li>--}}
                        <li >
                            <a href="{{ route('vendor.girvi.ledger') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                Ledger
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('vendor.girvi.list') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                List
                            </a>
                        </li>
                        
                    </ul>
                </li>
				<li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#jewrpr" aria-expanded="false">
                        <div> <i class="far fa-circle nav-icon "></i> Jwlr Rpr </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="jewrpr" class="collapse " data-parent="#compact_submenuSidebar">
                        <li >
                            <a href="{{ route('vendor.girvi.repair') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                New/Old 
                            </a>
                        </li>
                        
                    </ul>
                </li>
                <li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#jewexchng" aria-expanded="false">
                        <div> <i class="far fa-circle nav-icon "></i> Jwlr Exchng </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="jewexchng" class="collapse " data-parent="#compact_submenuSidebar">
                        <li >
                            <a href="{{ route('vendor.exchange.index') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                New/Old 
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('vendor.exchange.reciept') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                Receipt
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('vendor.exchange.list') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                List
                            </a>
                        </li>
                        
                    </ul>
                    
                </li>
                <li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#buyselll" aria-expanded="false">
                        <div> <i class="far fa-circle nav-icon "></i> Buy/Sell </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="buyselll" class="collapse " data-parent="#compact_submenuSidebar">
                        <li >
                            <a href="{{ route('vendor.buy-sell.index') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                New/Old 
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('vendor.buy-sell.reciept') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                Receipt
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('vendor.buy-sell.list') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                List
                            </a>
                        </li>
                    </ul>
                    
                </li>
                <li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#inex" aria-expanded="false">
                        <div> <i class="far fa-circle nav-icon "></i> Income/Expence </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="inex" class="collapse " data-parent="#compact_submenuSidebar">
                        <li >
                            <a href="{{ route('vendor.rfid.income') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                New
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('vendor.rfid.list') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                List
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#hrm" aria-expanded="false">
                        <div> <i class="far fa-circle nav-icon "></i> HR manage</div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="hrm" class="collapse " data-parent="#compact_submenuSidebar">
                        <li >
                            <a href="{{ route('vendor.hrm.index') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                List
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('vendor.hrm.view') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                View
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('vendor.hrm.add') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                New 
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('vendor.hrm.attendance') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                Attandance
                            </a>
                        </li>
                    </ul>
                </li>
				
            </ul>
            
        </div>
	

        <div class="submenu {{ @$ecomorders }}" id="ecommerce-admin">
            <div class="menu-title">
                <h3>Ecommerce Admin</h3>
            </div>
            <ul class="submenu-list" data-parent-element="#ecommerce-admin">

                <li>
                    <a href = "{{ route('ecomdashboard') }}" class="">
                    <i class="nav-icon fas fa-tachometer-alt"></i> Ecommerce Dashborad
                    </a>
                </li>

                <li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#Products" aria-expanded="false">
                        <div> <i class="far fa-circle nav-icon "></i> Products </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="Products" class="collapse " data-parent="#compact_submenuSidebar">
                        <li >
                            <a href="{{ route('ecomproducts.index') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                              Listed
                            </a>
                        </li>

                        <li >
                            <a href="{{ route('ecomstocks.index') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                              Not Listed
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href = "{{ route('catalogues.index') }}" class="nav-link ">
                        <i class="far fa-circle nav-icon "></i>
                          Catalogues
                    </a>
                </li>

                <li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#ecom-cat" aria-expanded="false">
                        <div>  <i class="far fa-circle nav-icon "></i> Ecom Categories </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="ecom-cat" class="collapse " data-parent="#compact_submenuSidebar">
                        <li >
                            <a href="{{ route('ecomcategories.show',1) }}"  >
                            <i class="far fa-circle nav-icon"></i>
                            {{ category_label(1) }}
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('ecomcategories.show',2) }}"  >
                            <i class="far fa-circle nav-icon"></i>
                            {{ category_label(2) }}
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('ecomcategories.show',3) }}"  >
                            <i class="far fa-circle nav-icon"></i>
                            {{ category_label(3) }}
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#ecom-miscellaneous" aria-expanded="false">
                        <div>  <i class="far fa-circle nav-icon "></i> Miscellaneous </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="ecom-miscellaneous" class="collapse " data-parent="#compact_submenuSidebar">
                        <li >
                            <a href = "{{ route('ecomslider') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                           Slider
                            </a>
                        </li>
                        <li >
                            <a href = "{{ route('aboutcontent') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                           About Us
                            </a>
                        </li>
                        <li >
                            <a href = "{{ route('ecommwebinformations.index') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                           Web Information
                            </a>
                        </li>
                        <li >
                            <a href = "{{ route('sociallink') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                           Social Links
                            </a>
                        </li>
                        <li >
                            <a href = "{{ route('termsandconditions') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                           Terms & Conditions
                            </a>
                        </li>
                        <li >
                            <a href = "{{ route('privacypolicy') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                           Privacy Policy
                            </a>
                        </li>
						<li >
                            <a href = "{{ route('refundpolicy') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                           Refund Policy
                            </a>
                        </li>
                        <li >
                            <a href = "{{ route('desclaimer') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                           Desclaimer
                            </a>
                        </li>
						<li >
                            <a href = "{{ route('shiping') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                           Shiping Policy
                            </a>
                        </li>
						 <li >
                            <a href = "{{ route('acdelete') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                            AC Deletion Policy
                            </a>
                        </li>
                    </ul>
                </li>
				<hr>
				@php 
                    if(in_array($branch,['orderdetail','productordertxns','schemetxns'])){
                        $show = " show";
                        $active = ' active';
                    }else{
                        $show = "";
                        $active = '';
                    }
                @endphp
                <li class="sub-submenu {{ @$active }} {{ @$show }}">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#Orders" aria-expanded="false">
                        <div> <i class="far fa-circle nav-icon "></i> Orders </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="Orders" class="collapse {{ @$show }}" data-parent="#compact_submenuSidebar">
                        <li class="{{ @$orderdetail }}{{ @$productordertxns }}">
                            <a href = "{{ route('ecomorders.products') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon "></i>
                                Products
                            </a>
                        </li>
                        <li class="{{ @$schemetxns }}">
                            <a href = "{{ route('ecomorders.schemes') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon "></i>
                                Schemes
                            </a>
                        </li>
                    </ul>
                </li>
				
                <li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#txns" aria-expanded="false">
                        <div> <i class="far fa-circle nav-icon "></i> Transactions </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="txns" class="collapse " data-parent="#compact_submenuSidebar">
                        <li>
                            <a href = "{{ route('ecomorders.txns','order') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon "></i>
                                Products
                            </a>
                        </li>

                        <li>
                            <a href = "{{ route('ecomorders.txns','scheme') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon "></i>
                                Schemes
                            </a>
                        </li>
                    </ul>
                </li>
				
                <li>
                    <a href = "{{ route('ecomorders.cart') }}" class="nav-link ">
                        <i class="far fa-circle nav-icon "></i>
                        Cart
                    </a>
                </li>
            </ul>
        </div>
		@php  
            $target_link = str_replace('.',"_",Route::currentRouteName()) ;
            $$target_link = 'active';
        @endphp
        <div class="submenu" id="Inventory">
            <div class="menu-title">
                <h3>Inventory</h3>
            </div>
            <ul class="submenu-list" data-parent-element="#Inventory">
                <li>
                    <a href="{{ route('stocks.home') }}" >
                    <i class="far fa-circle nav-icon"></i> Jewellery Stock
                    </a>
                </li>
				
				<li >
                    <a href="{{ route('stockcounters.index') }}" >
                    <i class="far fa-circle nav-icon"></i> Counter Stock
                    </a>
                </li>
				
            </ul>
        </div>

        <div class="submenu" id="schemes">
            <div class="menu-title">
                <h3>Schemes</h3>
            </div>
			<li  id="custo_li">
                <a href = "{{ route('shopschemes.txnmsgrecord') }}/scheme" style="width:inherit;">
                    <i class="fa-solid fa-comment-sms">‌</i>Text Messages <b class="fa fa-list" style="float:right;"></b>
                </a>
            </li>
			<li  id="custo_li">
                <a href = "{{ route('customers.index') }}" style="width:inherit;">
                    <i class = "far fa-user nav-icon"></i> Customers <b style="float:right;">&#x276F&#x276F;</b>
                </a>
            </li>
            <style>
            #custo_li{
                background:white;
                margin:10px;
                padding: 9px 12px;
                list-style:none;
                border-radius:10px;
            }
            #custo_li>a{
                display:block;
                width:100%;
                font-weight:bold;
            }
            </style>
            <ul class="submenu-list" data-parent-element="#schemes">
				 <li>
                    <a href="{{ route('shopschemes.enquiries') }}" style="font-weight:bold;display:block;">
                    &#x26A0;&nbsp;ENQUIRIES&nbsp;<b style="float:right;">&#x27A4;<span class="badge badge-info">{{ enquirycount() }}</span></b>
                    </a>
                </li>
                <li >
                    <a href="{{ route('shopscheme.index') }}" >
                        <i class="far fa-circle nav-icon"></i> All Scheme
                    </a>
                </li>
                <li >
                    <a href="{{ route('group.index') }}" >
                        <i class="far fa-circle nav-icon"></i> Scheme Groups
                    </a>
                </li>

                <li >
                    <a href="{{ route('enrollcustomer.index') }}" >
                        <i class="far fa-circle nav-icon"></i> Advance Enroll Customer
                    </a>
                </li>

                <li>
                    <a href="{{ route('shopscheme.pay') }}" >
                        <i class="far fa-circle nav-icon"></i> Add Money
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('shopscheme.due') }}" >
                        <i class="far fa-circle nav-icon"></i> Due Amount
                    </a>
                </li>
				<li>
					<a href="{{ route('shopscheme.due.list') }}">
						<i class="far fa-circle nav-icon"></i> Due List
					</a>
				</li>

                
                <li>
                    <a href="{{ route('shopscheme.daybooksummery') }}" >
                        <i class="far fa-circle nav-icon"></i> Day Book
                    </a>
                </li>
				<hr>
				<li>
                    <a href="{{ route('anjuman.dashboard') }}" >
                        <i class="far fa-square nav-icon"></i> Anjuman
                    </a>
                </li>
                {{--<li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#anjumanscheme" aria-expanded="false">
                        <div> <i class="far fa-square nav-icon "></i> Anjuman </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="anjumanscheme" class="collapse " data-parent="#compact_submenuSidebar">
                        <li >
                            <a href="{{ route('anjuman.all.scheme') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                Schemes
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('anjuman.all.enroll') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                Enroll Customer
                            </a>
                        </li>

                        <li >
                            <a href="{{ route('anjuman.all.txns') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                                Payments
                            </a>
                        </li>
                        
                    </ul>
                </li>--}}
            </ul>
        </div>

        <div class="submenu" id = "miscellaneous">
            <div class="menu-title">
                <h3>Miscellaneous</h3>
            </div>
            <ul class="submenu-list" data-parent-element="#miscellaneous">
                    <li >
                        <a href = "{{ route('suppliers.index') }}" >
                        <i class = "far fa-circle nav-icon"></i> Suppliers
                        </a>
                    </li>
                    <li>
                        <a href = "{{ route('customers.index') }}" >
                        <i class = "far fa-circle nav-icon"></i> Customers
                        </a>
                    </li>
                    <li>
                        <a href = "{{ route('shopbranches.index') }}" >
                        <i class = "far fa-circle nav-icon"></i> Shop Branches
                        </a>
                    </li>
                    <li>
                        <a href = "{{ route('counters.index') }}" >
                        <i class = "far fa-circle nav-icon"></i> Shop Counters
                        </a>
                    </li>
                    <li>
                        <a href = "{{ route('designations.index') }}" >
                        <i class = "far fa-circle nav-icon"></i> Employee Designations/Roles
                        </a>
                    </li>
                    <li >
                        <a href = "{{ route('employees.index') }}" >
                        <i class = "far fa-circle nav-icon"></i> Employees
                        </a>
                    </li>
                    <li >
                        <a href = "{{ route('settings.index') }}" >
                        <i class = "far fa-circle nav-icon"></i> Settings
                        </a>
                    </li>
                    <li >
                        <a href = "{{ route('subscriptions') }}" >
                        <i class = "far fa-circle nav-icon"></i> Subscriptions
                        </a>
                    </li>

                <li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#Products" aria-expanded="false">
                        <div> <i class="far fa-circle nav-icon "></i> Product Categories </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="Products" class="collapse " data-parent="#compact_submenuSidebar">
                        <li >
                            <a href="{{ route('categories.show',1) }}"  >
                            <i class="far fa-circle nav-icon"></i> {{ category_label(1) }}
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('categories.show',2) }}"  >
                            <i class="far fa-circle nav-icon"></i> {{ category_label(2) }}
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('categories.show',3) }}"  >
                            <i class="far fa-circle nav-icon"></i> {{ category_label(3) }}
                            </a>
                        </li>
                    </ul>
                </li>
				<li>
                    <a href = "{{ route('banking.index') }}" >
                    <i class = "far fa-circle nav-icon"></i> Banking/Billing
                    </a>
                </li>
            </ul>
        </div>
		
		<div class="submenu" id="udhari">
            <div class="menu-title">
                <h3>Udhari</h3>
            </div>
            <ul class="submenu-list" data-parent-element="#udhari">
                <li>
                    <a href="{{ route('udhar.create') }}" >
                    <i class="far fa-circle nav-icon"></i> Udhar 
                    </a>
                </li>
				<li>
                    <a href="{{ route('udhar.index') }}" >
                    <i class="far fa-circle nav-icon"></i> Udhar  List
                    </a>
                </li>
                <li>
                    <a href="{{ route('udhar.ledger') }}">
                    <i class="far fa-circle nav-icon"></i> Udhar  Ledger
                    </a>
                </li>
            </ul>
        </div>
		
		<div class="submenu" id="girvi">
            <div class="menu-title">
                <h3>Girvi</h3>
            </div>
            <ul class="submenu-list" data-parent-element="#girvi">
                <li>
                    <a href="{{ route('girvi.create') }}" >
                        <i class="far fa-circle nav-icon"></i> Girvi
                    </a>
                </li>
                <li class="{{ @$girvi_ladgerbook }}">
                    <a href="{{ route('girvi.ladgerbook') }}" >
                        <i class="far fa-circle nav-icon"></i> Girvi Ledger
                    </a>
                </li>
                <li>
                    <a href="{{ route('girvi.grirvilist') }}" >
                        <i class="far fa-circle nav-icon"></i> Girvi List
                    </a>
                </li>
            </ul>
        </div>
		
		<div class="submenu" id="billing">
            <div class="menu-title">
                <h3>Billing</h3>
            </div>
            <ul class="submenu-list" data-parent-element="#billing">
                <li >
                    <a href="{{ route('purchases.index') }}" >
                    <i class="far fa-circle nav-icon"></i> Purchase
                    </a>
                </li>
                <li >
                    <a href="{{ route('sells.index') }}" >
                    <i class="far fa-circle nav-icon"></i> Sell
                    </a>
                </li>
                <hr>
                <li >
                    <a href="{{ route('bills.index') }}" >
                    <i class="far fa-circle nav-icon"></i> Just Bill
                    </a>
                </li>
                <hr>
                <li >
                    <a href="{{ route('gst.report') }}" >
                    <i class="far fa-circle nav-icon"></i> GST Report
                    </a>
                </li>
            </ul>
        </div>
		
		
		<div class="submenu" id="intregation">
            <div class="menu-title">
                <h3>Integration</h3>
            </div>
            <ul class="submenu-list" data-parent-element="#intregation">
				<li>
                    <a href = "{{ route('mygateway.index') }}" >
                    <i class = "far fa-circle nav-icon"></i> Payment Gateway
                    </a>
                </li>
                <li>
                    <a href = "{{ route('textmsgeapi.index') }}" >
                    <i class = "far fa-circle nav-icon"></i> TextMsg Api
                    </a>
                </li>
            </ul>
        </div>
		<div class="submenu" id="RFID-Barcode">
            <div class="menu-title">
                <h3>Product Tags</h3>
            </div>
            <ul class="submenu-list" data-parent-element="#RFID-Barcode">
				{{--<li>
                    <a href = "{{ route('idtags.index') }}" >
                    <i class = "far fa-circle nav-icon"></i> Scane/Match
                    </a>
                </li>
                <li>
                    <a href = "{{ route('idtags.index') }}" >
                    <i class = "far fa-circle nav-icon"></i> Assign
                    </a>
                </li>
                <li>
                    <a href = "{{ route('idtags.create') }}" >
                    <i class = "far fa-circle nav-icon"></i> Generate/Print
                    </a>
                </li>
				<hr>--}}
				<li>
                    <a href = "{{ route('idtags.match') }}" >
                    <i class = "far fa-circle nav-icon"></i> Scane/Match
                    </a>
                </li>
				<li>
                    <a href = "{{ route('idtags.generate') }}" >
                    <i class = "far fa-circle nav-icon"></i> Generate/Print
                    </a>
                </li>
            </ul>
        </div>
		
		<div class="submenu" id="Jwellery-Repair">
            <div class="menu-title">
                <h3>Jwellery Repair</h3>
            </div>
            <ul class="submenu-list" data-parent-element="#intregation">
				<li>
                    <a href = "{{ route('jewellery.repair') }}" >
                    <i class = "far fa-circle nav-icon"></i> New/Old
                    </a>
                </li>
            </ul>
        </div>

        <div class="submenu" id="Old-Jwellery-Exchange">
            <div class="menu-title">
                <h3>Old Jwellery Exchange</h3>
            </div>
            <ul class="submenu-list" data-parent-element="#intregation">
				<li>
                    <a href = "{{ route('jewellery.exchange','create') }}" >
                    <i class = "far fa-circle nav-icon"></i> New/Old
                    </a>
                </li>
                <li>
                    <a href = "{{ route('jewellery.exchange','receipt') }}" >
                    <i class = "far fa-circle nav-icon"></i> Receipt
                    </a>
                </li>
                <li>
                    <a href = "{{ route('jewellery.exchange','list') }}" >
                    <i class = "far fa-circle nav-icon"></i> List
                    </a>
                </li>
            </ul>
        </div>

        <div class="submenu" id="HR-Management">
            <div class="menu-title">
                <h3>HR Management</h3>
            </div>
            <ul class="submenu-list" data-parent-element="#intregation">
				<li>
                    <a href = "{{ route('human.resource','create') }}" >
                    <i class = "far fa-circle nav-icon"></i> New
                    </a>
                </li>
                <li>
                    <a href = "{{ route('human.resource','list') }}" >
                    <i class = "far fa-circle nav-icon"></i> List
                    </a>
                </li>
                <li>
                    <a href = "{{ route('human.resource','view') }}" >
                    <i class = "far fa-circle nav-icon"></i> View
                    </a>
                </li>
                <li>
                    <a href = "{{ route('human.resource','attandance') }}" >
                    <i class = "far fa-circle nav-icon"></i> Attandance
                    </a>
                </li>
            </ul>
        </div>
		
{{-- ================================================================================================== --}}

</div>
