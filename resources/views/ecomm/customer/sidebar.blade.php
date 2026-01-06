<ul id="dashboard_side">  
    <li class="{{ @$wish}}"><a href="{{ url("{$ecommbaseurl}wishlist") }}"><i class="fas fa-heart"></i> My Wishlist</a></li>
    <li class="{{ @$cart}}"><a href="{{ url("{$ecommbaseurl}cart")}}"><i class="fas fa-shopping-cart"></i> My Cart</a></li>
	<li class="{{ @$enquiry}}">
        <a href="{{ url("{$ecommbaseurl}enquiries") }}">
            <i class="fas fa-paper-plane"></i>  
            Enquiries
        </a>
    </li>  
    {{-- <hr> --}}
    <li class="{{ @$scheme}}">
        <a href="{{ url("{$ecommbaseurl}schemes") }}">
            <i class="fas fa-handshake"></i>  
            My Scheme
        </a>
    </li> 
    <li class="{{ @$order}}"><a href="{{ url("{$ecommbaseurl}orders")}}"><i class="fas fa-bookmark"></i> Orders</a></li>
    <li class="{{ @$txns}}"><a href="{{ url("{$ecommbaseurl}transactions")}}"><i class="fa fa-briefcase"></i> Transactions</a></li>
    {{-- <hr> --}}
    <li class="{{ @$profile}}"><a href="{{ url("{$ecommbaseurl}profile") }}"><i class="fa fa-user"></i> My Profile</a></li>
    <li class="{{ @$password }}"><a href="{{ url("{$ecommbaseurl}password")}}"><i class="fa fa-lock"></i> My Password</a></li>
    {{-- <hr> --}}
    <li class="{{ @$logout}}"><a href="{{ url("{$ecommbaseurl}logout") }}"><i class="fa fa-share-square"></i> Logout </a></li>
</ul>