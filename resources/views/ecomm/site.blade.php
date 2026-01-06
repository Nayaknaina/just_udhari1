
@php

    $info = $common_content['info'] ;
    $socials = $common_content['socials'] ;

    $social_true = (!$socials) ? false : true ;
    $info_true = (!$info) ? false : true ;

    if($info_true){

        $web_title = $info['title'] ;
        $head_mail = $info['email'] ;
        $head_fone = "+91-".$info['mobile_no'] ;
        $logo = "assets/ecomm/logos/".$info['logo'] ;
        $address = $info['address'] ;
      $web_color = $info['web_color'] ;

    }else{

        $web_title = '' ; 
        $head_mail = 'example@gamil.com' ;
        $head_fone = "+91-9876543210";
        $logo =  'assets/ecomm/logos/no_logo.png';
        $address =  '23/4 Bhopal MP 404133 ';
        $web_color =  'black';

    }
    
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title') {{ $web_title }} </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
	@stack('metatags')
    <meta content="All Jewellery At One Place Bhopal Madhypradesh" name="keywords">
    <meta content="Mg Jewellers have wide range & verity of Jewellery at Bhopal Madhyapradesh" name="description">

    <!-- Favicon -->
    <link href="{{ asset($logo)}}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('assets/ecomm/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('assets/ecomm/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset("assets/ecomm/css/own_custom_{$web_color}.css") }}" rel="stylesheet">
    <link href="{{ asset('assets/ecomm/css/style2.css') }}" rel="stylesheet">
    
    @stack('css')

    <style>

        .breadcrumb-section {

            padding: 100px 0 50px 0px;

        }

        .breadcrumb-section h1{
            text-align: center;
        }

        #custo_menu_sub  {
            
            background: #00000054;
            padding: 5px;
            border: none;
            border-radius: 10px;

        }

        #custo_menu_sub li { 

            background: #FFF ;
            padding: 5px 10px;
            border-bottom: 1px solid rgb(0, 0, 0, .2);
            border-radius: 10px;
            box-shadow: 0px 1px 0px 0px rgb(0, 0, 0, .1);
            margin-bottom: 5px;

        }
        
        #custo_menu_sub a {
                color: #6c757d;
                text-decoration: none;
                background-color: transparent;
        }

        #custo_menu_sub hr {

            display: none;

        }

        #dashboard_side {
            list-style: none;
            padding: 0;
            box-shadow: 0px 0px 1px 1px rgb(0, 0, 0, .2);
        }

        #dashboard_side>li {
            padding: 10px 10px;
            /* box-shadow: inset 0px -1px 0px 0px rgb(0, 0, 0, .1); */
            border-bottom: 1px dotted rgb(0, 0, 0, .2);
        }

        @media screen and (max-width: 768px) {

            .top_header, .top_menue_bar {
                background: #FFF !important;
            }

            #mob_custo_menu_sub hr {

                display: none;

            }

            #mob_custo_menu_sub {

                background: #00000054;
                padding: 5px;
                border: none;
                border-radius: 10px;

            }

            #mob_custo_menu_sub li { 

                background: #FFF ;
                padding: 5px 10px;
                border-bottom: 1px solid rgb(0, 0, 0, .2);
                border-radius: 10px;
                box-shadow: 0px 1px 0px 0px rgb(0, 0, 0, .1);
                margin-bottom: 5px;

            }
            .navbar ul {

                margin-top: auto;
                
            }

            #custo_main, #mob_custo_main {
                border: none ;
                padding: 5px 10px;
                border-radius: 10px;
                font-size: 20px;
                text-decoration: none;
                box-shadow: 1px 2px 3px 1px rgb(0, 0, 0, .2);
            }

            .navbar-light .navbar-toggler {
                color: rgba(0, 0, 0, 0.5);
                border-color: transparent;
                padding: 10px;
                border-radius: 10px;
                box-shadow: 1px 2px 3px 1px rgb(0, 0, 0, .2);
            }

            #mob_custo_menu_sub a {
                color: #6c757d;
                text-decoration: none;
                background-color: transparent;
            }

        }

    </style>

</head>

<body>

    @include('ecomm.layout.header', ['activemenu' => @$activemenu])
    @yield('stylesheet')
    @yield('content')
    @include('ecomm.layout.footer')

    <!------------------INDEX PAGE JS---------------------------------------------------->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/ecomm/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets/ecomm/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Contact Javascript File -->
    <!--<script src="{{ asset('assets/ecomm/mail/jqBootstrapValidation.min.js') }}"></script>
    <script src="{{ asset('assets/ecomm/mail/contact.js') }}"></script>-->

    <!-- Template Javascript -->
    <!------------------END INDEX PAGE JS------------------------------------------------>
    @stack('js')
	
    <script src="{{ asset('assets/ecomm/js/main.js') }}"></script>
    @yield('javascript')
	
	<script>
		
		
		function checkOrientation() {
			if (window.orientation === 0 || $(window).width() < $(window).height()) {
				if(window.innerWidth <= 768){
					var header_block_height = $('div.top_header').outerHeight(); 
					$('div#header_seprator').css('height',header_block_height+"px");
				}
			}else{
				$('div#header_seprator').css('height','');
			}
		}
        $(document).ready(function(){
			
			$(window).on('resize orientationchange', checkOrientation);
			checkOrientation(); // Check on page load
			
            getshoppinglistcount();
            $(".remove_from_kart,.remove_from_wishlist,.move_to_cart").click(function(e){
                e.preventDefault();
				var q = false;
				if(!$(this).hasClass('move_to_cart')){
					q = confirm("Sure to Remove ?");
				}else{
					q=true;
				}
                const tr = $(this).parent('td').parent('tr');
				const tr_count = $('tbody > tr').length;
				const qnt_actn = tr.hasClass('genuine')?false:true
				const sum_unit_rate = $("#total_unit_price").text();
				const sum_lbr_chrg = $("#total_sum_labour").text();
				const sum_total_rate = $("#total_sum_price").text();
				const final_quant = $("#final_quantity").text();
				const final_rate = $("#final_subtotal").text();
				const payable = $("#final_amount").text();
                if(q==true){
                    const url = $(this).attr('href');
                    $.get(url,"",function(response){
                        alert(response.msg);
                        if(response.status){
                            const data_arr = (response.data).split("#");
                            const type_arr = ["wishlist","kart"];
                            $("#"+(type_arr[data_arr[0]])+"_count").text(data_arr[1]);
							const qnt = (!qnt_actn)?1:tr.find('.unit_qnt').text();
							const lbr = tr.find('.unit_labour').text();
							const price = tr.find('.unit_price').text();
							const sub_price = tr.find('.sub_price').text();
							
							var new_qnt = final_quant-qnt;
							$("#final_quantity").empty().text(new_qnt);
							
							var new_unit_price = sum_unit_rate-price;
							$("#total_unit_price").empty().text(new_unit_price.toFixed(2));
							
							var new_lbr_chrg = sum_lbr_chrg-lbr;
							$("#total_sum_labour").empty().text(new_lbr_chrg.toFixed(2));
							
							var new_ttl_price = sum_total_rate-sub_price;
							$("#total_sum_price").empty().text(new_ttl_price.toFixed(2));
							
							var new_final_cost = final_rate-sub_price;
							$("#final_subtotal").empty().text(new_final_cost.toFixed(2));
							
							var new_payable_cost = payable-sub_price;
							$("#final_amount").empty().text(new_payable_cost.toFixed(2));
							if(tr_count==1){
								const check_btn = '<button type="button" class="btn btn-block btn-primary my-3 py-3 text-white" onclick="alert(\'Your Cart is Empty !\');">Checkout ?</button>';
								$('[name="checkout"]').replaceWith(check_btn);
							}
                            tr.remove();
                            getshoppinglistcount();
                        }
                    });
                }
            });
            /*$(".move_to_cart").click(function(e){
                e.preventDefault();
                const tr = $(this).parent('td').parent('tr');
                $.get($(this).attr('href'),"",function(response){
                    alert(response.msg);
                    if(response.status){
                        tr.remove();
                        getshoppinglistcount();
                    }
                })
            });*/
            function getshoppinglistcount(){
                $.get("{{ url("{$ecommbaseurl}soppinglistcount")}}","",function(response){
					$("#wishlist_count").empty().text(response.wish_list);
					$("#kart_count").empty().text(response.kart_list);
				});
            }
            
			$.get('{{ route("shop.rate") }}',"",function(response){
                if(response.rates_arr){
                    const rates = response.rates_arr;
                    if(rates.gold){
                        $.each(rates.gold,function(i,v){
							
                            $(`#rate_gold_${i}`).text((v)?(Math.round(v * 10)).toLocaleString("en-IN"):'-');
                        });
                    }
                    if(rates.silver){
                        $.each(rates.silver,function(i,v){
                            $(`#rate_silver_${i}`).text((v)?(Math.round(v)).toLocaleString("en-IN"):'-');
                        });
                    }
					if(rates.date){
						$(`#rate_update`).text(rates.date);
					}
                }
            });
			
            $("#state").change(function(){
                const state_code = $(this).val();
                if(state_code!=""){
                    const data = {state:state_code};
                    $.get("{{ url("{$ecommbaseurl}get-districts"); }}",data,function(response){
                        if(response.length > 0){
                            option='<option value="">Select District !</option>';
                            $.each(response,function(i,v){
                                option+='<option value="'+(v.code)+'">'+(v.name)+'</option>';
                            });
                        }else{
                            option='<option value="">No District !</option>';
                        }
                        $("#dist").empty().append(option);
                    });
                }
            });

            $("#ship_state").change(function(){
                const state_code = $(this).val();
                if(state_code!=""){
                    const data = {state:state_code};
                    $.get("{{ url("{$ecommbaseurl}get-districts"); }}",data,function(response){
                        if(response.length > 0){
                            option='<option value="">Select District !</option>';
                            $.each(response,function(i,v){
                                option+='<option value="'+(v.code)+'">'+(v.name)+'</option>';
                            });
                        }else{
                            option='<option value="">No District !</option>';
                        }
                        $("#ship_dist").empty().append(option);
                    });
                }
            });
            $('.addr_state').change(function(){
                const state_code = $(this).val();
                if(state_code!=""){
                    const target = $(this).attr('data-target');
                    $("#"+target).empty().append('<option>Loading...</option>');
                    const data = {state:state_code};
                    $.get("{{ url("{$ecommbaseurl}get-districts"); }}",data,function(response){
                        if(response.length > 0){
                            option='<option value="">Select District !</option>';
                            $.each(response,function(i,v){
                                option+='<option value="'+(v.code)+'">'+(v.name)+'</option>';
                            });
                        }else{
                            option='<option value="">No District !</option>';
                        }
                        $("#"+target).empty().append(option);
                    });
                }
            })
            $("#page_await").hide();
        });
		function digitonly(event,num){
            let inputValue = event.target.value;

                // Allow only digits using regex
                inputValue = inputValue.replace(/[^0-9]/g, '');  // Remove anything that's not a digit

                // Ensure that the input has exactly 10 digits
                if (inputValue.length > num) {
                    inputValue = inputValue.slice(0, 10);  // Trim to 10 digits
                }

                // Update the input field with the valid input
                event.target.value = inputValue;
        }
  </script>
  <script>
    $(window).on('scroll', function() {
		
			fixheader();
		
    });
	fixheader();
	function fixheader(){
		if(window.innerWidth >= 768){
			var scrollTop = $(this).scrollTop();
			var header = $('#head_search_bar');
			var headheight = header.outerHeight();
			var menu_div = $('.top_header');
			var menu_div_height = menu_div.outerHeight();
			var top_menu_bar_cntnr = $('#top_menu_bar_container');
			var to_menu_cntnr_hgt = headheight+menu_div_height;
			if (scrollTop > 50) { // Change 50 to your preferred scroll trigger
				header.addClass('fixed');
				menu_div.addClass('fixed');
				menu_div.css('top',headheight+"px");
				$("#header_seprator").css('height',to_menu_cntnr_hgt+'px');
			} else {
				header.removeClass('fixed');
				menu_div.removeClass('fixed');
				menu_div.css('top',"unset");
				$("#header_seprator").css('height','inherit');
			}
		}
	}
  </script>

<!--Divyanshu's Coder--->
	<script>
        $('#top-search-field > input[name="search_term"]').focus(function(){
            $(this).parent("div#top-search-field").removeClass('minimul').addClass('normal');
        });
       $('#top-search-field > input[name="search_term"]').on('blur',function(){
            $(this).parent("div#top-search-field").removeClass('normal').addClass('minimul');
        });


    </script>

   <script>
(function () { 
  const hamburger = document.getElementById('hamburger');
  const menu = document.getElementById('mobileMenu');
  const backdrop = document.getElementById('menuBackdrop');
  const closeBtn = document.getElementById('menuClose');

  function openMenu() {
    menu.classList.add('open');
    backdrop.classList.add('visible');
    menu.setAttribute('aria-hidden', 'false');
    hamburger.setAttribute('aria-expanded', 'true');
    backdrop.setAttribute('aria-hidden', 'false');
    // Move focus inside the menu (to close button)
    closeBtn.focus();
    // prevent body scroll while open
    document.documentElement.style.overflow = 'hidden';
  }

  function closeMenu() {
    menu.classList.remove('open');
    backdrop.classList.remove('visible');
    menu.setAttribute('aria-hidden', 'true');
    hamburger.setAttribute('aria-expanded', 'false');
    backdrop.setAttribute('aria-hidden', 'true');
    // restore focus to hamburger
    hamburger.focus();
    document.documentElement.style.overflow = '';
  }

  hamburger.addEventListener('click', function (e) {
    if (menu.classList.contains('open')) closeMenu();
    else openMenu();
  });

  closeBtn.addEventListener('click', closeMenu);
  backdrop.addEventListener('click', closeMenu);

  // Close on Escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && menu.classList.contains('open')) {
      closeMenu();
    }
  });

  // ✅ Close only if link is outside Categories section
  menu.addEventListener('click', function (e) {
    if (
      e.target.tagName === 'A' &&
      !e.target.closest('#mobileCategories') // agar Categories ke andar h to ignore
    ) {
      closeMenu();
    }
  });

  // Optional: support swipe to close on touch devices
  (function addSwipeClose() {
    let startX = null;
    menu.addEventListener('touchstart', (e) => { startX = e.touches[0].clientX; });
    menu.addEventListener('touchmove', (e) => {
      if (!startX) return;
      const currentX = e.touches[0].clientX;
      const dx = currentX - startX;
      // if user swipes right-to-left, do nothing; if left-to-right swipe (dx > 40) close
      if (dx > 40) {
        closeMenu();
        startX = null;
      }
    });
    menu.addEventListener('touchend', () => { startX = null; });
  })();
})();
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const catLink = document.querySelector(".mob_cat");

  if (catLink) {
    catLink.addEventListener("click", function (e) {
      e.preventDefault(); // Bootstrap ka default collapse behavior stop
      e.stopPropagation();

      const targetId = catLink.getAttribute("data-target");
      const target = document.querySelector(targetId);

      if (target.classList.contains("show")) {
        target.classList.remove("show");
      } else {
        target.classList.add("show");
      }
    });
  }
});
</script>

{{-- -divyanshu code to icon animation --}}
<script>
document.getElementById('hamburger').addEventListener('click', function () {
  const menu = document.getElementById('mobileMenu');
  // On open, reflow icons so CSS animation restarts reliably
  if (!menu.classList.contains('open')) {
    // open is added by your existing script — wait microtick then force reflow
    setTimeout(() => { void menu.offsetWidth; }, 20);
  }
});
</script>
<script>
    // Remove + and - and spaces from number
    var phone = "{{ $head_fone }}".replace(/\D/g, ''); // keep only digits

    // Pre-filled message
    var message = encodeURIComponent("Hello, I want more details about your services.");

    // Detect device
    var isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

    var whatsappLink = document.getElementById("whatsapp_out");

    if (isMobile) {
        whatsappLink.href = "https://wa.me/" + phone + "?text=" + message;
    } else {
        whatsappLink.href = "https://web.whatsapp.com/send?phone=" + phone + "&text=" + message;
    }
</script>

</body>

</html>