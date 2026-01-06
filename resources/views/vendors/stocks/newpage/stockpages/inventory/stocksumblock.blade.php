<style>
ul#stock_sum{
	list-style: none;
	display: inline-flex;
	flex-wrap:wrap;
	gap:2px;
	box-shadow: 1px 1px 5px 1px #ff947b;
	border-radius:5px;
}
ul#stock_sum>li{
	margin:auto;
	padding:0 5px;
	border:1px solid gray;
	border-radius:5px;
	box-shadow:1px 2px 3px -4px lightgray;
}
ul#stock_sum>li>b{
	border-right:1px solid lightgray;
	padding-right:2px;
}
ul#stock_sum>li>span{
	padding-left:2px;
	color:blue;
}
ul#stock_sum>li>span.gm:after{
	content:'Gm';
}
ul#stock_sum>li>span.rs:after{
	content:'Rs';
}
</style>

<ul id="stock_sum" class="p-0 mb-1">
    @switch($stock_title)
        @case('stone')
        @case('silver')
		 @case('gold')
            <li>
                <b>GROSS</b>
                    <span class="gm">{{ $stock_sum->sum_gross??'-' }} </span>
            </li>
            <li>
                <b>NET</b>
                    <span class="gm" >{{ $stock_sum->sum_net??'-' }} </span>
            </li>
            <li>
                <b>FINE</b>
                    <span class="gm">{{ $stock_sum->sum_fine??'-' }} </span>
            </li>
            @break
        @case('artificial')
            <li>
                <b>PIECE</b>
                    <span class="" >{{ $stock_sum->sum_count??'-' }} </span>
            </li>
			<li>
                <b>TOTAL</b>
                    <span class="rs" >{{ $stock_sum->sum_total??'-' }} </span>
            </li>
            @break
		@case('franchise-jewellery')
            <li>
                <b>GROSS</b>
                    <span class="gm" id="">{{ $stock_sum->sum_gross??'-' }} </span>
            </li>
            <li>
                <b>NET</b>
                    <span class="gm" id="">{{ $stock_sum->sum_net??'-' }} </span>
            </li>
            <li>
                <b>PIECE</b>
                    <span class="" id="">{{ $stock_sum->sum_count??'-' }} </span>
            </li>
            @break
        @default
            <li>
                <b class="text-danger">No Summery !</b>
            </li>
            @break;
    @endswitch
</ul>