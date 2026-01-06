	/*var top_pos = $(".theme-logo").outerHeight() ;

    $("#open_menu").css('top',top_pos+"px");*/
	
    function activateMenuFromURL() {

        const currentURL = window.location.href ;
        // console.log(window.innerWidth);
        if (window.innerWidth > 991 ) {
            // console.log(2) ;
            $('.menu a').filter(function() {
                return currentURL.includes(this.href);
            }).parents('.menu').addClass('active') ;

            $('.submenu-list li a').each(function () {

            var submenu_id =  $(this).parents('.submenu').attr('id') ;

                if(currentURL.includes($(this).attr('href'))) {

                    $(this).parents('li').addClass('active');

                    $(this).parents('.submenu').each(function () {
                        $(this).addClass('show');
                        $(this).siblings('.menu-toggle').addClass('active');
                        var main_menu_id = $(this).attr('id') ;
                        $('.menu a').each(function () {
                            if ('#'+main_menu_id === $(this).attr('href')) {
                                $(this).closest('.menu').addClass('show');
                                $('.submenu-sidebar').addClass('show');
								/*//----For Slider menu----//
								var menu_slider = $("#open_menu");
                                var text = $(this).closest('.menu').text();
                                //menu_slider.empty().text(text.replace(/\s+/g, ' ').trim());
                                menu_slider.attr('href',$(this).closest('a.menu-toggle').attr('href'));
                                menu_slider.empty().text(text.replace(/\s+/g, ' ').trim());
								//---End For Slider Menu----//
								*/
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

       }
    }

    activateMenuFromURL() ;

    function success_sweettoatr(value){

        swal({
            title: value,
            type: 'success',
            padding: '2em'
        }) ;

    } ;
