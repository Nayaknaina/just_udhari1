<style>
    
    /*#imp_links_ul{
        /*border-top:1px solid gray;
        background:lightgray;
        background:#d3d3d370;
        padding-top:5px;
        /*box-shadow: 1px 2px 3px gray inset;
        position:relative;
        border-radius: 15px 15px 0 0;
        /* padding:0 5px; *
        min-height:auto!important;
        margin-top: auto;
        margin-right:auto;
    }*/
	#imp_links_ul{
        /*border-top:1px solid gray;
        background:lightgray;
        background:#d3d3d370;(/
        padding-top:5px;
        /*box-shadow: 1px 2px 3px gray inset;*/
        position:relative;
        border-radius: 15px 15px 0 0;
        /* padding:0 5px; */
        min-height:auto!important;
		margin-right:auto;
    }
    #imp_short_cut{
        display:none;
    }
    .imp_link_li{
        position:relative;
        border:1px solid #ff6e26;
        /*background: linear-gradient(to bottom,silver,white,white,white,silver);*/
        border-radius:10px;
        margin:0 15px;
        color: #ff6e26;
        /* font-size: 120%; */
    }
    
    .imp_link_li > a{
        color: #ff6e26;
    }
    .imp_link_li > .submenu{
        display:none;
        position:absolute;
        top:100%;
        right:0;
        width:max-content;
        background:#eee;
        background:white;
        border-radius:10px;
        padding:5px 0px;
        border:1px dashed gray;
        box-shadow: rgba(0, 0, 0, 0.25) 0px 0.0625em 0.0625em, rgba(0, 0, 0, 0.25) 0px 0.125em 0.5em, rgba(255, 255, 255, 0.1) 0px 0px 0px 1px inset;
    }
    .imp_link_li > .submenu>a{
        color:#ff6e26;
        display:block;
        padding:2px 10px;
    }
    .imp_link_li > .submenu>a:hover{
        background:#ff6e262e;
    }
    #imp_links_ul > .imp_link_li:hover{
        background: linear-gradient(to bottom,#ff6e26,white,white,white,white,white,#ff6e26); 
    }
    .imp_link_li:hover .submenu{
        display:block;
    }
    @media  (max-width: 1024px) {
            .navbar .navbar-item.search-ul {
        margin: 0 0 auto;

               
        display: flex;
        align-items: center;
        gap: 30px;
            }
        #imp_short_cut{
            display:block;
            cursor:pointer;
            margin: auto;
            /* margin-left:auto; */
        }
        #imp_links_ul{
            position:absolute;
            top:100%;
            display:none;
            right:20%;
            box-shadow:unset;
            padding:0;
            background:unset;
            border:unset;
            z-index:1;
        }
        .imp_link_li{
            background:white;
            padding:5px!important;
            margin:unset;
        }
        .imp_link_li:hover +a{
            background:white;
            font-weight:bold;
        }
        .imp_link_li > .submenu{
            position:inherit;
            /*box-shadow:1px 2px 3px */
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        }
        #imp_links_ul > .imp_link_li:hover{
            background:white;
        }
    }

     /* ------------------------------------------ */
    /* ✅ NOTIFICATION STYLES ADDED BELOW ONLY     */
    /* ------------------------------------------ */

    .notif-icon-wrap {
        margin-left: auto;
        margin-right: 15px;
        display:flex;
        align-items:center;
    }

    .notif-icon {
        font-size: 20px;
        color: #ff6e26;
        cursor: pointer;
        padding: 4px;
        border-radius: 50%;
        transition: 0.2s;
    }

    .notif-icon:hover {
        background: #ff6e262e;
    }

    .notif-panel {
        position: fixed;
        top: 0;
        right: -320px;
        width: 300px;
        height: 100vh;
        background: #fff;
        box-shadow: -4px 0px 12px rgba(0, 0, 0, 0.15);
        border-radius: 12px 0 0 12px;
        transition: right 0.3s ease;
        z-index: 9999;
        padding: 15px;
    }

    .notif-panel.active {
        right: 0;
    }

    .notif-header {
        font-weight: 700;
        font-size: 18px;
        display: flex;
        justify-content: space-between;
    }

    .notif-header i {
        cursor: pointer;
        color: #999;
        font-size: 20px;
    }

    .notif-body {
        margin-top: 20px;
        font-style: italic;
    }

    .notif-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.25);
        display: none;
        z-index: 9990;
    }

    .notif-overlay.active {
        display: block;
    }
</style>
<div id="imp_short_cut" class="hamburger">&#9780;</div>
<ul id="imp_links_ul" class="navbar-item flex-row" >
	<li class="imp_link_li" style="padding:0 5px;">
		<a href="#"><span class="fa fa-pencil-square"></span>
		Billing <span class="fa fa-caret-down"></span>  
		</a>
		<div class="submenu">
		{{--<a href="{{ route('purchases.create') }}" >
				 Purchase
		</a>--}}
			<a href="{{ route('billing',['sale']) }}" >
				 Sell
			</a>
			<a href="{{ route('bills.create') }}" >
				 Just Bill
			</a>
			<a href="{{ route('gst.report') }}" >
				 GST Report
			</a>
		</div>
	</li>
	<li class="imp_link_li" style="padding:0 5px;">
		<a href="#"><span class="fa fa-database"></span>
		Inventory <span class="fa fa-caret-down"></span>  
		</a>
		<div class="submenu">
		{{--<a href="{{ route('stocks.home') }}" >
			 Jewellery Stock
			</a>
			<a href="{{ route('stockcounters.index') }}" >
			 Counter Stock
			</a>--}}
			<a href="{{ route('stock.new.dashboard') }}" >
			 Dashboard
			</a>
			<hr class="m-0">
			<a href="{{ route('stock.new.create') }}">
				<i><b>Add Stock</b></i>
			</a>
			<a href="{{ route('stock.idtags.scane') }}">
				<i><b>Check Stock</b></i>
			</a>
		</div>
	</li>
	<li class="imp_link_li" style="padding:0 5px;">
		<a href="#"><span class="fa fa-pie-chart"></span>
		Udhar <span class="fa fa-caret-down"></span>  
		</a>
		<div class="submenu">
			<a href="{{ route('udhar.create') }}" >
			New/ Bhaw Cut
			</a>
			<a href="{{ route('udhar.index') }}" >
			Udhar  List
			</a>
			<a href="{{ route('udhar.ledger') }}">
			Udhar  Ledger
			</a>
		</div>
	</li>
	<li class="imp_link_li" style="padding:0 5px;">
		<a href="#" ><span class="fa fa-handshake"></span>
		Scheme <span class="fa fa-caret-down"></span>  
		</a>
		<div class="submenu">
			<a href="{{ route('shopscheme.index') }}" >
				All Scheme
			</a>
			<a href="{{ route('group.index') }}" >
				Scheme Groups
			</a>
			<a href="{{ route('enrollcustomer.index') }}" >
				Advance Enroll Customer
			</a>
			<a href="{{ route('shopscheme.pay') }}" >
				Add Money
			</a>
			<a href="{{ route('shopscheme.due') }}" >
				Due Amount
			</a>
			<a href="{{ route('shopscheme.daybooksummery') }}" >
				Day Book
			</a>
			<hr class="m-1 border-gray">
			<a href="{{ route('anjuman.dashboard') }}">
				<i><b>Anjuman</b></i>
			</a>
		</div>
	</li>
    

</ul>


<!-- 
<script>
document.getElementById('openNotif').addEventListener('click', function() {
    document.getElementById('notifPanel').classList.add('active');
    document.getElementById('notifOverlay').classList.add('active');
});

document.getElementById('closeNotif').addEventListener('click', function() {
    document.getElementById('notifPanel').classList.remove('active');
    document.getElementById('notifOverlay').classList.remove('active');
});

document.getElementById('notifOverlay').addEventListener('click', function() {
    document.getElementById('notifPanel').classList.remove('active');
    document.getElementById('notifOverlay').classList.remove('active');
});
</script> -->