
<style>
    .section_title{
        background:white;
        background:#fff3e0;
        position: relative;
        padding:0 10px 0 0;
        border-radius: 0 2rem 0 0;
        /*box-shadow: 3px 1px 3px gray;*/
        border-top:1px solid #ffcc80;
        border-right:1px solid #ffcc80;
        border-left:1px solid #ffcc80;
    }
    .section_title>.head{
        margin:0;
        padding:5px;
        /*color:#ff6e26;*/
        color:#ef6c00;
        font-weight:bold;
    }
    .breadcrump_link_main,.breadcrump_link,.breadcrump_link_mid{
        color:#ff6e26!important;
    }
    .breadcrump_link_main{
        font-weight:bold;
    }
    ul.section_info{
        padding:0;
        display: flex;
        flex-wrap: wrap;
        list-style:none;
        margin:0;
        /*box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
        border-top:1px dashed gray;*/
    }
    .section_action{
        margin: auto auto 0 0;
        padding:0 1%;
    }
    .section_action >a.btn{
        border-radius:15px;
    }
	.section_action{
        margin: auto 0;
        padding:0 0.5%;
		text-align:center;
    }
    .section_action >a.btn{
        border-radius:15px;
        margin:1px;
    }
    .section_route{
        align-content:end;
        margin-left:auto; 
        padding:0 5px;
    }
    .route_ul{
        list-style-position: inside;
        display:flex;
        padding:0;
        flex-wrap: wrap;
    }
    .route_ul>li{
        padding:0 0 0 5px;
        word-wrap: anywhere;
    }
    .route_ul > li::marker{
        content:"\276D  ";
    }
    .route_ul > li:has(> a.breadcrump_link_main)::marker {
        content: "\27A7";
    }
    
    @media (max-width: 768px) {
        li.section_route{
            display:none;
        }
        li.section_action{
            margin:auto;
        }
        /*.route_ul li {
            order: unset;
            width: auto;
            margin: 0;
            text-align: left;
        }*/
    }
</style>

<section class="content-header m-0 p-0" >
    <div class="container-fluid p-0">
        <ul class="section_info w-100">
            <li class="section_title">
                <h1 class="head">{{ $datas['title'] }}</h1>
            </li>
            @if(count($anchor) > 0)
            <li class="section_action">
                @foreach($anchor as $ak=>$a)
                {!! @$a  !!}
                @endforeach
            </li>
            @endif
            <li class="section_route">
                <ul class="route_ul">
                    <li><a href="#" class="breadcrump_link_main">Home</a></li>
                    <!--<li><a href="#" class="breadcrump_link_mid">Home</a></li>-->
					@if(count($datas['sub_title']) > 0)
                        @foreach($datas['sub_title'] as $sk=>$sub)
                        <li><a href="{{ $sub }}" class="breadcrump_link_mid">{{ $sk }}</a></li>
                        @endforeach
                    @endif
                    <li>{{ $datas['title'] }}</li>
                </ul>
                {{--<div class="d-inline-flex route">
                    <p class="m-0"><a href="#" class="breadcrump_link_main">Home</a></p>
                    <p class="m-0 px-2">-</p>
                    <p class="m-0" class="breadcrump_link">{{ $datas['title'] }}</p>
                </div>--}}
            </li>
        </ul>
    </div><!-- /.container-fluid -->
</section>
