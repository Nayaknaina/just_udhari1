<div class="sidebar-wrapper sidebar-theme">
    <style>
        .menu-categories.expand{
            height:calc(100vh - 50px)!important;
        }
		.menu-categories li>a{
			color: #505d70;
		}
        /*.menu-categories.collapse > li:not(.menu_toggle_btns),
        .menu-categories.collapse > li > button#cstm_menu_collapse,
        .menu-categories.expand > li > button#cstm_menu_expande{
            visibility:hidden;
            display:none;
        }*/
		nav#compactSidebar{
			font-family: "Roboto", Arial, sans-serif;
		}
		nav#compactSidebar.min >ul>  li .menu_title,
        li.menu_toggle_btns.collapse>button#cstm_menu_collapse,
        li.menu_toggle_btns.expand>button#cstm_menu_expande
        {
            visibility:hidden;
            display:none;
        }
        .menu-categories > li{
            visibility:visible;
            opacity: 1;
            transition: opacity 0.3s ease;
        }
		.menu-categories > li>a{
			display:flex;
			gap: 10px;
		}
        nav#compactSidebar{
            transition: width 0.3s ease;
        }
        nav#compactSidebar.min{
            /* transition: width 0.3s ease; */
            /*width:40px;*/
			width: 65px;
        }
        nav#compactSidebar >.menu-logo > a>span{
            opacity: 1;
            visibility: visible;
            transition: opacity 0.3s ease;
            /* transition: visibility 0.3s ease; */
        }
        nav#compactSidebar.min >.menu-logo > a>span{
            visibility: hidden;
            /* display:none; */
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        #content.main-content.expand,.header.navbar.expand{
            margin-left:65px;
        }
		
		/* === Sidebar Scrollbar Like YouTube === */
		.sidebar-wrapper nav {
		  scrollbar-width: thin;
		  /* Firefox */
		  scrollbar-color: transparent transparent;
		  transition: scrollbar-color 0.3s;
		}

		/* Hide scrollbar by default */
		.sidebar-wrapper nav::-webkit-scrollbar {
		  width: 6px;
		}

		.sidebar-wrapper nav::-webkit-scrollbar-track {
		  background: transparent;
		}

		.sidebar-wrapper nav::-webkit-scrollbar-thumb {
		  background: transparent;
		  border-radius: 3px;
		}

		/* Show thin scrollbar on hover (like YouTube) */
		.sidebar-wrapper:hover nav {
		  scrollbar-color: rgba(0, 0, 0, 0.3) transparent;
		}

		.sidebar-wrapper:hover nav::-webkit-scrollbar-thumb {
		  background: rgba(0, 0, 0, 0.3);
		}

		.sidebar-wrapper:hover nav::-webkit-scrollbar-thumb:hover {
		  background: rgba(0, 0, 0, 0.5);
		}
		.tooltip .tooltip-inner {
			/*background-color: #222;
			color: #ff0;
			font-size: 16px;
			padding: 10px 14px;
			border-radius: 8px;
			box-shadow: 0 4px 10px rgba(0,0,0,0.2);*/
		}
		@media (max-width: 768px) {
			.new-menu.min,
			.new-menu.max{
				z-index:0;
				
				/*margin-left:-150px;*/
			}
			.new-menu .menu-categories{
				border:1px dashed orange;
				box-shadow:1px 2px 3px lightgray;
			}
			#content.main-content{
				margin-left:0!important;
			}
		}
    </style>
    <nav id = "compactSidebar" class="new-menu min">        
        {{--<div class="theme-logo">
            <a href="{{ url('vendors/') }}">
                <img src = "{{ asset('assets/images/favicon.png') }}" class="navbar-logo" alt="logo">
            </a>
        </div>--}}
        {{--<div class="menu-logo">
            <a href="{{ url('vendors/') }}">
                <img src = "{{ asset('assets/images/favicon.png') }}" class="navbar-logo" alt="logo">
                <span>Just Udhari</span>
            </a>
        </div>--}}
        <ul class="menu-categories expand">
			<li class="main-menu {{ isActive('vendors.home') }}">
                <a href="{{ route('vendors.home') }}"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Dashboard">
					<!--<i class="fa fa-tv"></i>-->
				<span class="nav-icon" aria-hidden="true">
				  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M3 12l9-9 9 9"></path>
					<path d="M9 21V9h6v12"></path>
				  </svg>
				</span>
				<span class="menu_title"> Dashboard</span> </a>
            </li>

            <li class="main-menu has-sub {{ isActive(['*billing*','*bills','*gst/report']) }}" >
                <a href="javascript:void(null);" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Billing">
                <!--<i class="fa-solid fa-receipt"></i>-->
                <span class="nav-icon" aria-hidden="true">
                  <!-- Invoice / Bill -->
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 2h6l2 2h4v18H3V4h4z"></path>
                    <line x1="9" y1="8" x2="15" y2="8"></line>
                    <line x1="9" y1="12" x2="15" y2="12"></line>
                    <line x1="9" y1="16" x2="13" y2="16"></line>
                  </svg>
                </span>
                <span class="menu_title"> Billing</span></a>
                <ul  class="sub-menu">
                {{--<li class="main-menu has-sub">
                        <a href="javascript:void(null);"> Purchase</a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('billing',['purchase']) }}"> New </a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('purchases.index') }}"> Purchase</a></li>
                    <li><a href="{{ route('sells.index') }}"> Sell</a></li>--}}
                    <li class="main-menu has-sub  {{ isActive('*billing/sale*') }}">
                        <a href="javascript:void(null);"> Sell</a>
                        <ul class="sub-menu">
                            <li class="{{ isActive('*billing/sale/create') }}"><a href="{{ route('billing',['sale']) }}"> New </a></li>
                            <li class="{{ isActive('billing.all') }}"><a href="{{ route('billing.all',['sale']) }}"> All </a></li> 
                        </ul>
                    </li>
                    <li class="{{ isActive('bills.index') }}"><a href="{{ route('bills.index') }}"> Just Bill</a></li>
                    <li class="{{ isActive('gst.report') }}"><a href="{{ route('gst.report') }}"> GST Report</a></li>
                </ul>
            </li>
            <li class="main-menu has-sub {{ isActive("stock.new*") }}">
                <a href="javascript:void(null);" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Inventory">
                <!--<i class="fa fa-solid fa-warehouse"></i>-->
                <span class="nav-icon" aria-hidden="true">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="7"></rect>
                    <rect x="3" y="14" width="18" height="7"></rect>
                  </svg>
                </span>
                <span class="menu_title"> Inventory</span></a>
                <ul class="sub-menu">
                    <li class="{{ isActive("stock.new.dashboard") }}"><a href="{{ route('stock.new.dashboard') }}"> Dashboard </a></li>
                    <li><a href="{{ route('stock.new.item') }}">Items/Group</a></li>
                    <li class="{{ isActive("stock.new.create") }}"><a href="{{ route('stock.new.create') }}"> New Stock</a></li>
                    <li class="{{ isActive("stock.new.inventory.import") }}"><a href="{{ route('stock.new.inventory.import') }}"> Import Stock</a></li>
                    <li class="main-menu has-sub {{ isActive(["stock.new.inventory*","stock.new.groupinventory*"]) }}">
                        <a href="javascript:void(null);"> All Stock </a>
                        <ul class="sub-menu">
                            <li class="{{ isActive(["stock.new.groupinventory*","stock.new.inventory.item"]) }}"><a href="{{ route('stock.new.groupinventory','stock=gold') }}"> Group Wise </a></li>
                            <li class="{{ isActive("stock.new.inventory") }}"><a href="{{ route('stock.new.inventory','stock=gold') }}"> Item Wise </a></li>
                        </ul>
                    </li>
                    {{--<li class="main-menu has-sub {{ isActive("stock.new.inventory") }}">
                        <a href="javascript:void(null);"> All Stock </a>
                        <ul class="sub-menu">
                            <li class="{{ isActive("stock.new.inventory",['stock'=>'gold']) }}"><a href="{{ route('stock.new.inventory','stock=gold') }}"> Gold </a></li>
                            <li class="{{ isActive("stock.new.inventory",['stock'=>'silver']) }}"><a href="{{ route('stock.new.inventory','stock=silver') }}"> Silver </a></li>
                        </ul>
                    </li>
                    <li class="main-menu has-sub {{ isActive("stock.new.groupinventory") }}">
                        <a href="javascript:void(null);"> All Group Stock </a>
                        <ul class="sub-menu">
                            <li class="{{ isActive("*.groupinventory",['stock'=>'gold']) }}"><a href="{{ route('stock.new.groupinventory','stock=gold') }}"> Gold </a></li>
                            <li class="{{ isActive("stock.new.inventory",['stock'=>'silver']) }}"><a href="{{ route('stock.new.groupinventory','stock=silver') }}"> Silver </a></li>
                        </ul>
                    </li>--}}
                    {{--<li><a href="{{ route('stocks.home') }}"> Jewellery Stock</a></li>
                    <li><a href="{{ route('stockcounters.index') }}"> Counter Stock</a></li>--}}

                    <li class="{{ isActive('*.idtags.scane') }}"><a href="{{ route('stock.idtags.scane') }}"> Scane/Match</a></li>
                    <li class="{{ isActive('*.idtags.generate') }}"><a href="{{ route('stock.idtags.generate') }}"> Generate/Print</a></li>
                </ul>
            </li>

            <li class="main-menu has-sub {{ isActive('udhar.*') }}">
                <a href="javascript:void(null);"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Udhari">
                <!--<i class="fa-solid fa-coins"></i>-->
                <span class="nav-icon" aria-hidden="true">
                  <!-- Coin (Udhari) -->
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M12 7v10M8 12h8"></path>
                  </svg>
                </span>
                <span class="menu_title"> Udhari</span></a>
                <ul  class="sub-menu">
                    <li class="{{ isActive('udhar.create') }}"><a href="{{ route('udhar.create') }}"> Udhar</a></li>
                    <li class="{{ isActive('udhar.index') }}"><a href="{{ route('udhar.index') }}"> Udhar  List</a></li>
                    <li class="{{ isActive('udhar.ledger') }}"><a href="{{ route('udhar.ledger') }}"> Udhar  Ladger</a></li>
                </ul>
            </li>

            <li class="main-menu has-sub {{ isActive('*/girvi/*') }}">
                <a href="javascript:void(null);"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Girvi">
                <!--<i class="fa fa-database"></i>-->
                <span class="nav-icon" aria-hidden="true">
                  <!-- Bank (Girvi) -->
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 10h20L12 3 2 10z"></path>
                    <path d="M6 10v10M10 10v10M14 10v10M18 10v10"></path>
                    <path d="M4 20h16"></path>
                  </svg>
                </span>
                <span class="menu_title"> Girvi</span></a>
                <ul  class="sub-menu">
                    <li class="{{ isActive('*girvi/create') }}"><a href="{{ route('girvi.create') }}"> Girvi</a></li>
                    <li class="{{ isActive('*girvi/ladgerbook') }}"><a href="{{ route('girvi.ladgerbook') }}"> Girvi Ledger</a></li>
                    <li class="{{ isActive('*girvi/gritvilist') }}"><a href="{{ route('girvi.grirvilist') }}"> Girvi List</a></li>
                    <li class="{{ isActive('*girvi/all') }}"><a href="{{ route('girvi.list') }}"> List</a></li>
                </ul>
            </li>

            <li class="main-menu has-sub {{ isActive(["shopschemes.*","shopscheme.*","group.*","enrollcustomer.*","anjuman.*"]) }}">
                <a href="javascript:void(null);"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Schemes">
                <!--<i class="fa fa-solid fa-handshake"></i>-->
                <span class="nav-icon" aria-hidden="true">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 1v22"></path>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"></path>
                  </svg>
                </span>
                <span class="menu_title"> Schemes</span></a>
                <ul class="sub-menu">
                    <li class="{{ isActive('shopschemes.txnmsgrecord') }}"><a href="{{ route('shopschemes.txnmsgrecord') }}/scheme"> Text Messages</a></li>
                    <li class="{{ isActive('customers.index') }}"><a href="{{ route('customers.index') }}"> Customers</a></li>
                    <li class="{{ isActive('shopschemes.enquiries') }}"><a href="{{ route('shopschemes.enquiries') }}"> ENQUIRIES {{ enquirycount() }}</a></li>
                    <li class="{{ isActive('shopscheme.index') }}"><a href="{{ route('shopscheme.index') }}"> All Scheme</a></li>
                    <li class="{{ isActive('group.index') }}"><a href="{{ route('group.index') }}"> Scheme Groups</a></li>
                    <li class="{{ isActive('enrollcustomer.index') }}"><a href="{{ route('enrollcustomer.index') }}"> Advance Enroll Customer</a></li>
                    <li class="{{ isActive('shopscheme.pay') }}"><a href="{{ route('shopscheme.pay') }}"> Add Money</a></li>
                    <li class="{{ isActive('shopscheme.due') }}"><a href="{{ route('shopscheme.due') }}"> Due Amount</a></li>
                    <li class="{{ isActive('shopscheme.due.list') }}"><a href="{{ route('shopscheme.due.list') }}"> Due List</a></li>
                    <li class="{{ isActive('shopscheme.daybooksummery') }}"><a href="{{ route('shopscheme.daybooksummery') }}"> Day Book</a></li>
                    <li class="{{ isActive('anjuman.dashboard') }}"><a href="{{ route('anjuman.dashboard') }}"> Anjuman</a></li>
                        {{--<li class="main-menu has-sub {{ isActive('anjuman.all.*') }}">
                        <a href="javascript:void(null);"> Anjuman</a>
                        <ul class="sub-menu">
                            <li class="{{ isActive('anjuman.all.scheme') }}"><a href="{{ route('anjuman.all.scheme') }}"> Schemes</a></li>
                            <li class="{{ isActive('anjuman.all.enroll') }}"><a href="{{ route('anjuman.all.enroll') }}"> Enroll Customer</a></li>
                            <li class="{{ isActive('anjuman.all.txns') }}"><a href="{{ route('anjuman.all.txns') }}"> Pay Emi</a></li>
                        </ul>
                    </li>--}}
                </ul>
            </li>
            <li class="main-menu has-sub {{ isActive('*ecommerce*') }}">
                <a href="javascript:void(null);" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ecommerce Admin">
				<span class="nav-icon" aria-hidden="true">
				  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M6 6h15l-1.5 9H7.5z"></path>
					<path d="M6 6 5 3H3"></path>
				  </svg>
				</span>
				<span class="menu_title"> Ecommerce Admin</span>
				</a>
                <ul class="sub-menu">
                    <li class="{{ isActive('ecomdashboard') }}"><a href="{{ route('ecomdashboard') }}"><span class="menu_title">Ecommerce Dashborad</span></a></li>
                    <li class="main-menu has-sub {{ isActive(['ecomproducts.index','ecomstocks.index']) }}"><a href="javascript:void(null);" > Products</a>
                        <ul class="sub-menu">
                            <li class="{{ isActive('ecomproducts.index') }}"><a href="{{ route('ecomproducts.index') }}"> Listed</a></li>
                            <li class="{{ isActive('ecomstocks.index') }}"><a href="{{ route('ecomstocks.index') }}"> Not Listed</a></li>
                        </ul>
                    </li>
                    <li class="{{ isActive('catalogues.index') }}"><a href="{{ route('catalogues.index') }}"> Catalogues</a></li>
                    <li class="main-menu has-sub {{ isActive('*ecomcategories*') }}">
                        <a href="javascript:void(null);">Ecom Categories</a>
                        <ul class="sub-menu">
                            <li class="{{ isActive('*/ecomcategories/1') }}"><a href="{{ route('ecomcategories.show',1) }}"> {{ category_label(1) }}</a></li>
                            <li class="{{ isActive('*ecommerce/ecomcategories/2') }}"><a href="{{ route('ecomcategories.show',2) }}"> {{ category_label(2) }}</a></li>
                            <li class="{{ isActive('*ecomcategories/3') }}"><a href="{{ route('ecomcategories.show',3) }}"> {{ category_label(3) }}</a></li>
                        </ul>
                    </li>
                    <li class="main-menu has-sub {{ isActive(['ecomslider','aboutcontent','ecommwebinformations.index','sociallink','termsandconditions','privacypolicy','refundpolicy','desclaimer','shiping','acdelete']) }}">
                        <a href="javascript:void(null);"> Miscellaneous</a>
                        <ul class="sub-menu">
                            <li class="{{ isActive('ecomslider') }}"><a href="{{ route('ecomslider') }}"> Slider</a></li>
                            <li class="{{ isActive('aboutcontent') }}"><a href="{{ route('aboutcontent') }}"> About Us</a></li>
                            <li class="{{ isActive('ecommwebinformations.index') }}"><a href="{{ route('ecommwebinformations.index') }}"> Web Information</a></li>
                            <li class="{{ isActive('sociallink') }}"><a href="{{ route('sociallink') }}"> Social Links</a></li>
                            <li class="{{ isActive('termsandconditions') }}"><a href="{{ route('termsandconditions') }}"> Terms & Conditions</a></li>
                            <li class="{{ isActive('privacypolicy') }}"><a href="{{ route('privacypolicy') }}"> Privacy Policy</a></li>
                            <li class="{{ isActive('refundpolicy') }}"><a href="{{ route('refundpolicy') }}"> Refund Policy</a></li>
                            <li class="{{ isActive('desclaimer') }}"><a href="{{ route('desclaimer') }}"> Desclaimer</a></li>
                            <li class="{{ isActive('shiping') }}"><a href="{{ route('shiping') }}"> Shiping Policy</a></li>
                            <li class="{{ isActive('acdelete') }}"><a href="{{ route('acdelete') }}"> AC Deletion Policy</a></li>
                        </ul>
                    </li>
                    <li class="main-menu has-sub  {{ isActive(['ecomorders.products','ecomorders.schemes']) }}">
                        <a href="javascript:void(null);"> Orders</a>
                        <ul class="sub-menu">
                            <li class="{{ isActive('ecomorders.products') }}"><a href="{{ route('ecomorders.products') }}"> Products</a></li>
                            <li class="{{ isActive('ecomorders.schemes') }}"><a href="{{ route('ecomorders.schemes') }}"> Schemes</a></li>
                        </ul>
                    </li>
                    <li class="main-menu has-sub {{ isActive('ecomorders.txns') }}">
                        <a href="javascript:void(null);"> Transactions</a>
                        <ul class="sub-menu">
                            <li class="{{ isActive('*transactions/order') }}"><a href="{{ route('ecomorders.txns','order') }}"> Products</a></li>
                            <li class="{{ isActive('*transactions/scheme') }}"><a href="{{ route('ecomorders.txns','scheme') }}"> Schemes</a></li>
                        </ul>
                    </li>
                    <li class="{{ isActive('ecomorders.cart') }}"><a href="{{ route('ecomorders.cart') }}"> Cart</a></li>
                </ul>
            </li>
            <li class="main-menu has-sub {{ isActive(['suppliers.index','customers.index','shopbranches.index','counters.index','designations.index','employees.index','settings.index','subscriptions','categories.show']) }}">
                <a href="javascript:void(null);"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Miscellaneous">
				<!--<i class="fa fa-solid fa-gear"></i>-->
				<span class="nav-icon" aria-hidden="true">
				  <!-- Briefcase (Miscellaneous) -->
				    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                  <!-- Gear outer shape -->
                  <path d="M12 2 l1.2 2.6 a8.5 8.5 0 0 1 2.5 1 l2.7-1 2 3.5 -2.2 1.6 a8.5 8.5 0 0 1 0 2.8 l2.2 1.6 -2 3.5 -2.7-1 a8.5 8.5 0 0 1-2.5 1 L12 22 10.8 19.4 a8.5 8.5 0 0 1-2.5-1 l-2.7 1 -2-3.5 2.2-1.6 a8.5 8.5 0 0 1 0-2.8 l-2.2-1.6 2-3.5 2.7 1 a8.5 8.5 0 0 1 2.5-1 Z" />

                  <!-- Center circle -->
                  <circle cx="12" cy="12" r="3" />
                </svg>
				</span>
				<span class="menu_title"> Settings</span></a>
                <ul  class="sub-menu">
                    <li class="main-menu has-sub  {{ isActive('*billing/sale*') }}">
                        <a href="javascript:void(null);"> Miscellaneous</a>
                        <ul  class="sub-menu">
                            <li class="{{ isActive('suppliers.index') }}"><a href="{{ route('suppliers.index') }}"> Suppliers</a></li>
                            <li class="{{ isActive('customers.index') }}"><a href="{{ route('customers.index') }}"> Customers</a></li>
                            <li class="{{ isActive('shopbranches.index') }}"><a href="{{ route('shopbranches.index') }}"> Shop Branches</a></li>
                            <li class="{{ isActive('counters.index') }}"><a href="{{ route('counters.index') }}"> Shop Counters</a></li>
                            <li class="{{ isActive('designations.index') }}"><a href="{{ route('designations.index') }}"> Employee Designations/Roles</a></li>
                            <li class="{{ isActive('employees.index') }}"><a href="{{ route('employees.index') }}"> Employees</a></li>
                            <li class="{{ isActive('settings.index') }}"><a href="{{ route('settings.index') }}"> Settings</a></li>
                            <li class="{{ isActive('subscriptions') }}"><a href="{{ route('subscriptions') }}"> Subscriptions</a></li>
                            <li class="main-menu has-sub {{ isActive('categories.show') }}">
                                <a href="javascript:void(null);"><i class="fa fa-dashboard"></i> Product Categories</a>
                                <ul  class="sub-menu">
                                    <li class="{{ isActive('*settings/categories/1') }}"><a href="{{ route('categories.show',1) }}"> {{ category_label(1) }}</a></li>
                                    <li class="{{ isActive('*settings/categories/2') }}"><a href="{{ route('categories.show',2) }}"> {{ category_label(2) }}</a></li>
                                    <li class="{{ isActive('*settings/categories/3') }}"><a href="{{ route('categories.show',3) }}"> {{ category_label(3) }}</a></li>
                                </ul>
                            </li>
                            <li class="{{ isActive('banking.index') }}"><a href="{{ route('banking.index') }}"> Banking/Billing</a></li>
                            <li class="{{ isActive('currentrates') }}"><a href="{{ route('currentrates') }}"> Current Rate</a></li>
                        </ul>
                    </li>
                    <li class="main-menu has-sub {{ isActive(['mygateway.*','textmsgeapi.*']) }}" >
                        <a href="javascript:void(null);">
                        <span class="menu_title"> Integration</span></a>
                        <ul  class="sub-menu">
                            <li class="{{ isActive('mygateway.*') }}"><a href="{{ route('mygateway.index') }}"> Payment Gateway</a></li>
                            <li class="{{ isActive('textmsgeapi.*') }}"><a href="{{ route('textmsgeapi.index') }}"> TextMsg Api</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            {{--<li class="main-menu has-sub {{ isActive(['mygateway.*','textmsgeapi.*']) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Integration">
                <a href="javascript:void(null);">
				<!--<i class="fa-solid fa-puzzle-piece"></i>-->
				<span class="nav-icon" aria-hidden="true">
				  <!-- Puzzle (Integration) -->
				  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M14 2a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v2h-4a2 2 0 0 0-2 2v4h-2a2 2 0 0 1-2-2v-2H8a2 2 0 0 1-2-2V8h2a2 2 0 0 0 2-2V4a2 2 0 0 1 2-2h2z"></path>
				  </svg>
				</span>
				<span class="menu_title"> Integration</span></a>
                <ul  class="sub-menu">
                    <li class="{{ isActive('mygateway.*') }}"><a href="{{ route('mygateway.index') }}"> Payment Gateway</a></li>
                    <li class="{{ isActive('textmsgeapi.*') }}"><a href="{{ route('textmsgeapi.index') }}"> TextMsg Api</a></li>
                </ul>
            </li>--}}
            {{--<li class="main-menu has-sub {{ isActive('*.idtags.*') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Item Tags">
                <a href="javascript:void(null);">
				<!--<i class="fa-solid fa-tags"></i>-->
				<span class="nav-icon" aria-hidden="true">
				  <!-- Tag (Product Tags) -->
				  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M20 10V4H10L4 10l10 10 6-6z"></path>
					<circle cx="14" cy="6" r="1.5"></circle>
				  </svg>
				</span>
				<span class="menu_title"> Item Tags</span></a>
                <ul  class="sub-menu">
                    <li class="{{ isActive('*.idtags.scane') }}"><a href="{{ route('stock.idtags.scane') }}"> Scane/Match</a></li>
                    <li class="{{ isActive('*.idtags.generate') }}"><a href="{{ route('stock.idtags.generate') }}"> Generate/Print</a></li>
                </ul>
                {{--<ul  class="sub-menu">
                    <li><a href="{{ route('idtags.index') }}"> Scane</a></li>
                    <li><a href="{{ route('idtags.index') }}"> Assign</a></li>
                    <li><a href="{{ route('idtags.create') }}"> Generate/Print</a></li>
                    <li><a href="{{ route('idtags.match') }}"> New Scane/Match</a></li>
                    <li><a href="{{ route('idtags.generate') }}"> New Generate/Print</a></li>
                    <li><a href="{{ route('idtags.sizesetup') }}"> Size Setup</a></li>
                </ul>--}
            </li>--}}
            <li class="main-menu has-sub {{ isActive('jewellery.repair') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Jwellery Repair">
                <a href="javascript:void(null);">
				<!--<i class="fa-solid fa-screwdriver-wrench"></i>-->
				<span class="nav-icon" aria-hidden="true">
				  <!-- Tools (Jewellery Repair) -->
				  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M14.7 6.3a1 1 0 0 1 0 1.4L7.4 15H5v-2.4l7.3-7.3a1 1 0 0 1 1.4 0z"></path>
					<path d="M16 12l6 6"></path>
				  </svg>
				</span>
				<span class="menu_title"> Jwellery Repair</span></a>
                <ul  class="sub-menu">
                    <li class="{{ isActive('jewellery.repair') }}"><a href="{{ route('jewellery.repair') }}"> New/Old</a></li>
                </ul>
            </li>
            <li class="main-menu has-sub {{ isActive('jewellery.exchange') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Old Jwellery Exchange">
                <a href="javascript:void(null);">
				<!--<i class="fa-solid fa-retweet"></i>-->
				<span class="nav-icon" aria-hidden="true">
				  <!-- Refresh arrows (Exchange) -->
				  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M4 4v6h6"></path>
					<path d="M20 20v-6h-6"></path>
					<path d="M4 10a9 9 0 0 1 16-4"></path>
					<path d="M20 14a9 9 0 0 1-16 4"></path>
				  </svg>
				</span>
				<span class="menu_title"> Old Jwellery Exchange</span></a>
                <ul  class="sub-menu">
                    <li class="{{ isActive('*exchange/create') }}"><a href="{{ route('jewellery.exchange','create') }}"> New/Old</a></li>
                    <li class="{{ isActive('*exchange/receipt') }}"><a href="{{ route('jewellery.exchange','receipt') }}"> Receipt</a></li>
                    <li class="{{ isActive('*exchange/list') }}"><a href="{{ route('jewellery.exchange','list') }}"> List</a></li>
                </ul>
            </li>
            <li class="main-menu {{ isActive('shop.detail') }}"> 
                <a href="{{ route('shop.detail') }}"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Day-Book">
                    <!--<i class="fa fa-tv"></i>-->
                <span class="nav-icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
     stroke="currentColor" stroke-width="1.8"
     stroke-linecap="round" stroke-linejoin="round">

  <!-- Book shape -->
  <path d="M4 3h14a2 2 0 0 1 2 2v16H7a3 3 0 0 1-3-3V3z"/>

  <!-- Spine -->
  <path d="M7 3v18"/>

  <!-- DAY BOOK text -->
  <text x="9" y="16"
        font-size="5"
        font-family="Inter, system-ui, Arial, sans-serif"
        font-weight="700"
        fill="currentColor"
        stroke="none"
        transform="rotate(-90 12.5 16)">
    DAY
  </text>

  <text x="9" y="22"
        font-size="5"
        font-family="Inter, system-ui, Arial, sans-serif"
        font-weight="700"
        fill="currentColor"
        stroke="none"
        transform="rotate(-90 12.5 16)">
    BOOK
  </text>

</svg>




                </span>
                <span class="menu_title"> Day-Book</span> </a>
            </li>
            <li class="main-menu has-sub {{ isActive('*.resource') }}"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="HR Management">
                <a href="javascript:void(null);">
				<!--<i class="fa fa-street-view"></i>-->
				<span class="nav-icon" aria-hidden="true">
				  <!-- User (HR Management) -->
				  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<circle cx="12" cy="7" r="4"></circle>
					<path d="M5.5 21a7.5 7.5 0 0 1 13 0"></path>
				  </svg>
				</span>
				<span class="menu_title"> HR Management</span></a>
                <ul  class="sub-menu">
                    <li class=" {{ isActive('*resource/create') }}"><a href="{{ route('human.resource','create') }}"> New</a></li>
                    <li class=" {{ isActive('*resource/list') }}"><a href="{{ route('human.resource','list') }}"> List</a></li>
                    <li class=" {{ isActive('*resource/view') }}"><a href="{{ route('human.resource','view') }}"> View</a></li>
                    <li class=" {{ isActive('*resource/attandance') }}"><a href="{{ route('human.resource','attandance') }}"> Attandance</a></li>
                </ul>
            </li>

			{{--<li class="pb-5"></li>--}}
            {{--<li class="menu  {{ @$ecomorders }}">
                <a href="#sample-pages" data-active="false" class="menu-toggle" >
                    <div class="base-menu">
                        <div class="base-icons">
                            <img src = "{{asset('main/assets/img/ds-icon/e-comma-admin-inactive.svg')}}" class = "img-fluid icon_png">
                            <img src = "{{asset('main/assets/img/ds-icon/e-comma-admin.svg')}}" class = "img-fluid icon_png2">
                        </div>
                    </div>
              <span>Sample Pages</span>
             </a>
            </li>--}}



        </ul>


    </nav>

{{-- @include('layouts.vendors.subsidebar') --}}
</div>
<script>

</script>