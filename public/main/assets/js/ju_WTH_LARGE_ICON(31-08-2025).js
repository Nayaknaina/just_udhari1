
    var top_pos = $(".theme-logo").outerHeight() ;
    $("#open_menu").css('top',top_pos+"px");

    /*function activateMenuFromURL() {

        var currentURL = window.location.href ;
        
        var prepageUrl = document.referrer;
        
        // console.log(window.innerWidth);
        //if (window.innerWidth > 991 ) {
            // console.log(2) ;
            $('.menu a').filter(function() {
                return currentURL.includes(this.href);
            }).parents('.menu').addClass('active') ;
            currentURL = currentURL.split("?")[0];
            
            $('.submenu-list li a').each(function () {
                var submenu_id =  $(this).parents('.submenu').attr('id') ;
                var sub_href = $(this).attr('href')??false;
                
                //if(sub_href && (currentURL.includes(sub_href) || sub_href.startsWith(currentURL))) {
                if(sub_href && (currentURL.includes(sub_href))) {

                    $(this).parents('li').addClass('active');

                    $(this).parents('.submenu').each(function () {
                        $(this).addClass('show');
                        $(this).siblings('.menu-toggle').addClass('active');
                        var main_menu_id = $(this).attr('id') ;
                        $('.menu a').each(function () {
                            if ('#'+main_menu_id === $(this).attr('href')) {
                                $(this).closest('.menu').addClass('show active');
                                var menu_slider = $("#open_menu");
                                var menu = $(this).closest('.menu');
                                if(!menu.hasClass('menu-single')){
                                    var text = $(this).closest('.menu').text();
                                    //menu_slider.empty().text(text.replace(/\s+/g, ' ').trim());
                                    menu_slider.attr('href',$(this).closest('a.menu-toggle').attr('href'));
                                    menu_slider.empty().text(text.replace(/\s+/g, ' ').trim());
                                    menu_slider.show();
                                }else{
                                    menu_slider.hide();
                                }
                                //$('.submenu-sidebar').addClass('show');
                            }
                        });
                    });
                }

            });

            $('.sub-submenu a').each(function () {

                const link = $(this);
                
                if (currentURL.includes(link.attr('href'))) {
                    link.addClass('active');

                    // Activate parent items
                    link.parents('ul.collapse').each(function () {
                        $(this).addClass('show');
                        $(this).closest('.sub-submenu').addClass('show');
                    });

                    link.parents('.sub-submenu').each(function () {
                        $(this).addClass('show');
                        $(this).siblings('.menu-toggle').addClass('active') ;
                        var main_menu_id = $(this).attr('id');
                        $('.menu').each(function () {
                            $(this).find('a').each(function () {
                                if (link.attr('href') === $(this).attr('href')) {
                                    $(this).closest('.menu').addClass('show');
                                    $('.submenu-sidebar').addClass('show');
                                }
                            });
                        });
                    });
                }
            });
			//var currentURL = window.location.href ;
			var ele =  $(`.menu-toggle[href="${currentURL}"], .menu-toggle[data-target="${currentURL}"]`);
			var target = (ele.attr('href')?.startsWith('#'))?ele.attr('href'):false;

			ele.parent('li').addClass('active');
            const is_mobile = /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
            if (!is_mobile) {
                if(currentURL!=prepageUrl && target){
                    $(target).addClass('show');
                    $('.submenu-sidebar').addClass('show');
                    $("#side_close").show();
                    //ele.parent('li').addClass('active');
                }
            }
       //}
    }*/

    function activateMenuFromURL(){
        var currentURL = window.location.href ;        
        //alert(base_url);
        var prepageUrl = document.referrer;

        var ele =  $(`.menu-toggle[href="${currentURL}"], .menu-toggle[data-target="${currentURL}"]`);
        var target = (ele.attr('href')?.startsWith('#'))?ele.attr('href'):false;

        ele.parent('li').addClass('active');
        const is_mobile = /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
        if (!is_mobile) {
            if(currentURL!=prepageUrl && target){
                $(target).addClass('show');
                $('.submenu-sidebar').addClass('show');
                $("#side_close").show();
                $("#open_menu").text(ele.text());
                $("#open_menu").attr('href',ele.attr('href'));
                $("#open_menu").show();
                //ele.parent('li').addClass('active');
            }
        }

        var sub_m_ele =  ($(`.submenu-list >li >a[href="${currentURL}"]`).length > 0)?$(`.submenu-list >li >a[href="${currentURL}"]`):false;
		
        if(sub_m_ele){
            sub_m_ele.parent('li').addClass('active');
            var p_div = sub_m_ele.closest('div.submenu')
            var p_div_id = p_div.attr('id');
            let main = $(`.menu-toggle[href="#`+p_div_id+`"]`);
            main.parent('li').addClass('active');
            $("#open_menu").text(main.text());
            $("#open_menu").attr('href',"#"+p_div_id);
            $("#open_menu").show();
        }else{
            var dflt_sub = $(`.submenu-list >li.active`);
            var p_div = dflt_sub.closest('div.submenu')
            var p_div_id = p_div.attr('id');
            let main = $(`.menu-toggle[href="#`+p_div_id+`"]`);
            main.parent('li').addClass('active');
            $("#open_menu").text(main.text());
            $("#open_menu").attr('href',"#"+p_div_id);
            $("#open_menu").show();
        }
    }

    activateMenuFromURL();

    function success_sweettoatr(value){

        swal({
            title: value,
            type: 'success',
            padding: '2em'
        }) ;

    } ;

    $("#open_menu").hover(function(event){
        event.preventDefault();
        $('.submenu').removeClass('show');
        $($(this).attr('href')).addClass("show");
        $("#compact_submenuSidebar").addClass("show");
    });

    $("#open_menu").click(function(event){
        event.preventDefault();
        $('.submenu').removeClass('show');
        $($(this).attr('href')).addClass("show");
        $("#compact_submenuSidebar").addClass("show");
    });


    $('.sub-submenu').on('click,mouseenter',function(event){
        event.preventDefault();
        const id = $(this).find('a').data('target');
        $(id).addClass('show');
    });
    $('.sub-submenu').on('mouseleave',function(){
        const id = $(this).find('a').data('target');
        $(id).removeClass('show');
    });

    /** The Code For Top Sort Cut Menues */
    $("#imp_short_cut").on('click',function(e){
        e.preventDefault();
        $('#imp_links_ul').toggle();
    });
    
    // $('.imp_link_li').on('click',function(e){
    //     e.preventDefault();
    //     $('.imp_link_li>.submenu').hide();
    //     $(this).find('.submenu').toggle();
    // });


    $(".menu-toggle").click(function(e){
        e.preventDefault();
        if($(this).data('target')){
            window.location.href = $(this).data('target');
        }
    })

    $("#side_close").click(function(){
        $('.submenu-sidebar.show').removeClass('show');
        $('.menu-toggle.show').removeClass('show');
        $(this).hide();
    })

    var $el = $('li.menu.active');              // The active menu item
    var $container = $('.menu-categories');     // The scrollable container

    if ($el.length && $container.length) {

        var elTop = $el.position().top;             // Element's top within container
        
        var elHeight = $el.outerHeight();           // Element height
        
        var containerHeight = $container.height();  // Visible container height
        
        var scroll_height = $container[0].scrollHeight;
        
        var scrollTop = $container.scrollTop();     // Current scroll
        
        // Calculate the element's bottom inside the container
        var elBottom = (elTop + elHeight) + 40;
        
        //$container.animate({ scrollTop: scroll_height }, 300);
        // If the element is above the visible area
        if (elTop < 0) {
            $container.animate({ scrollTop: scrollTop + elTop }, 300);
        }
        // If the element is below the visible area
        else if (elBottom > containerHeight) {
            //alert(scrollTop + (elBottom - scroll_height));
            $container.animate({ scrollTop: scrollTop + (elBottom) }, 300);
            //$container.animate({ scrollTop: scrollTop + (scroll_height) }, 300);
        }
        // Else, already in view — do nothing
    }

