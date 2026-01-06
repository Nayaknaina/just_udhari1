var App = function() {
    var MediaSize = {
        xl: 1200,
        lg: 992,
        md: 991,
        sm: 576
    };
    var ToggleClasses = {
        headerhamburger: '.toggle-sidebar',
        inputFocused: 'input-focused',
    };

    var Selector = {
        getBody: 'body',
        mainHeader: '.header.navbar',
        headerhamburger: '.toggle-sidebar',
        fixed: '.fixed-top',
        mainContainer: '.main-container',
        sidebar: '#sidebar',
        sidebarContent: '#sidebar-content',
        sidebarStickyContent: '.sticky-sidebar-content',
        ariaExpandedTrue: '#sidebar [aria-expanded="true"]',
        ariaExpandedFalse: '#sidebar [aria-expanded="false"]',
        contentWrapper: '#content',
        contentWrapperContent: '.container',
        mainContentArea: '.main-content',
        searchFull: '.toggle-search',
        overlay: {
            sidebar: '.overlay',
            cs: '.cs-overlay',
            search: '.search-overlay'
        }
    };
    const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    const event_on = (isTouchDevice)?'click':'click mouseenter'; // Event for Open the Menu
    var toggleFunction = {
        sidebar: function($recentSubmenu) {
            $(document).on(event_on, '.sidebarCollapse', function (event) {
                event.preventDefault();
                var $getCompactSubmenuShow = $('#compact_submenuSidebar');
                var $get_mainContainer = $('.main-container');
                
                if ($getCompactSubmenuShow.hasClass('show') || $getCompactSubmenuShow.hasClass('hide-sub')) {
                    if ($getCompactSubmenuShow.hasClass('show')) {
                        $getCompactSubmenuShow.removeClass('show').addClass('hide-sub');
                        return;
                    }

                    if ($getCompactSubmenuShow.hasClass('hide-sub')) {
                        if ($get_mainContainer.hasClass('sidebar-closed')) {
                            $get_mainContainer.removeClass("sidebar-closed").addClass("sbar-open");
                            return;
                        }

                        if ($get_mainContainer.hasClass('sbar-open')) {
                            $get_mainContainer.removeClass('sbar-open');
                            $getCompactSubmenuShow.removeClass("hide-sub").addClass("show");
                            return;
                        }
                        $(Selector.mainContainer).addClass("sidebar-closed");
                    }
                } else {
                    $(Selector.mainContainer).toggleClass("sidebar-closed sbar-open");
                    if (window.innerWidth <= 991) {
                        // Handle overlay for small screens if needed
                    }
                    $('html, body').toggleClass('sidebar-noneoverflow');
                    $('footer .footer-section-1').toggleClass('f-close');
                }
            });
            $(document).on('mouseleave','.sidebar-theme',function(){
                if ($('#compact_submenuSidebar').hasClass('show')) {
                    $('.menu').removeClass('show');
                    $('#compact_submenuSidebar').removeClass('show');
                }
            });
        },
        overlay: function() {
            $(Selector.overlay.sidebar).on('click', function() {
                var windowWidth = window.innerWidth;
                if (windowWidth <= MediaSize.md) {
                    $(Selector.mainContainer).addClass('sidebar-closed');
                }
                $(Selector.overlay.sidebar).removeClass('show');
                $('html,body').removeClass('sidebar-noneoverflow');
                $('#compact_submenuSidebar').removeClass('show');
                $('.menu.show').removeClass('show');
                $('body').removeClass('mini_bar-open');
            });
        },
        search: function() {
            $(Selector.searchFull).click(function(event) {
                $(this).closest('.search-animated').find('.search-full').addClass(ToggleClasses.inputFocused);
                $(this).closest('.search-animated').addClass('show-search');
                $(Selector.overlay.search).addClass('show');
            });

            $(Selector.overlay.search).click(function(event) {
                $(this).removeClass('show');
                $(Selector.searchFull).closest('.search-animated').find('.search-full').removeClass(ToggleClasses.inputFocused);
                $(Selector.searchFull).closest('.search-animated').removeClass('show-search');
            });
        },
        navbarShadow: function() {
            var $getNav = $('.navbar');
            var $testDiv = $(".main-content");

            $(document).on('scroll', function() {
                var top = $(window).scrollTop();
                if (top >= $testDiv.offset().top) {
                    $getNav.css({"box-shadow": "0px 20px 20px rgba(126,142,177,0.12)", "background-color": "#fff"});
                } else {
                    $getNav.removeAttr("style");
                }
            });
        }
    };

    var inBuiltfunctionality = {
        mainCatActivateScroll: function() {
            new PerfectScrollbar('.menu-categories', {
                wheelSpeed: 0.5,
                swipeEasing: true,
                minScrollbarLength: 40,
                maxScrollbarLength: 100,
                suppressScrollX: true
            });
        },
        subCatScroll: function() {
            new PerfectScrollbar('#compact_submenuSidebar', {
                wheelSpeed: 0.5,
                swipeEasing: true,
                minScrollbarLength: 40,
                maxScrollbarLength: 100,
                suppressScrollX: true
            });
        },
        onSidebarClick: function() {
            $(document).on(event_on,'.menu', function() {
                var $cr_clsss = $(this).attr('class');
                var $get_body = $('body');
                // var $getHref = $(this).find('.menu-toggle').attr('href');
                // var $getElement = $('#compact_submenuSidebar > ' + $getHref);
                var $getMenuShowElement = $('.menu.show');
                var $getCompactSubmenu = $('#compact_submenuSidebar');
                var $getOverlayElement = $('.overlay');
                $('.menu').removeClass('show');
                $getCompactSubmenu.removeClass('show');
                if ($(this).hasClass('menu-single')) {
                    $(this).addClass('show');
                    return;
                } else {
                    var $getHref = $(this).find('.menu-toggle').attr('href');
                    var $getElement = $('#compact_submenuSidebar > ' + $getHref);
                    if ($getCompactSubmenu.length) {
                        $getCompactSubmenu.addClass('show').removeClass('hide-sub');
                        $get_body.addClass('mini_bar-open');
                        if (window.innerWidth > 991) {
                            $('.main-container').removeClass('sbar-open');
                        }
                    }

                    var $getElementActiveClass = $('#compact_submenuSidebar > .show');
                    if ($getElementActiveClass.length) {
                        $getElementActiveClass.removeClass('show');
                    }

                    if ($getMenuShowElement.length && $cr_clsss === 'menu show') {
                        $getMenuShowElement.removeClass('show');
                        $getCompactSubmenu.removeClass("show");
                    } else {
                        $(this).addClass('show');
                        //$(this).removeClass('active').addClass('show');
                    }
                    $getElement.addClass('show');
                }
            });
        },
        
        preventScrollBody: function() {
            // Prevent scroll functionality can be added if needed.
        },
        languageDropdown: function() {
            $('.more-dropdown .dropdown-item').each(function() {
                $(this).on('click', function() {
                    $('.more-dropdown .dropdown-toggle > img').attr('src', 'assets/img/' + $(this).data('img-value') + '.svg');
                });
            });
        }
    };

    /*
        Production Functionality - Only for Online files not for user files
    */
    var productionFunctionality = {
        createButtons: function() {
            var form = [
                {
                    type: 'anchor',
                    label: 'Buy Now',
                    target: '_blank'
                },
                {
                    type: 'button',
                    label: ''
                }
            ];

            var $element = $('<div>').addClass('online-actions').css({position: 'fixed', bottom: '43px', right: '21px', 'z-index': '1025'});

            form.forEach(function(obj) {
                switch (obj.type) {
                    case "button":
                        var $button = $('<button>').addClass('btn btn-secondary scroll-top-btn').html('<svg style="width: 15px; height: 15px; stroke-width: 2; vertical-align: middle;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>');
                        $element.append($button);
                        break;

                    case "anchor":
                        var $anchor = $('<a>').addClass('btn btn-danger buy-btn').attr('href', "https://themeforest.net/item/cork-responsive-admin-dashboard-template/25582188").attr('target', obj.target).html(obj.label);
                        $element.append($anchor);
                        break;
                }
            });

            $('body').append($element);
        },

        scrollTop: function() {
            $(document).on('click', '.scroll-top-btn', function(event) {
                event.preventDefault();
                $('html, body').animate({scrollTop: 0}, 500, 'swing');
            });
        },

        checkScrollPosition: function() {
            $(document).scroll(function() {
                var $lanWrapper = $('.layout-spacing');
                var $elementScrollToTop = $('.scroll-top-btn');
                var windowScroll = $(window).scrollTop();
                var elementoffset = $lanWrapper.offset().top;

                if (windowScroll >= elementoffset) {
                    $elementScrollToTop.addClass('d-inline-block');
                } else {
                    $elementScrollToTop.removeClass('d-inline-block');
                }
            });
        }
    };

    var _mobileResolution = {
        onRefresh: function() {
            var windowWidth = window.innerWidth;
            if (windowWidth <= MediaSize.md) {
                toggleFunction.sidebar(true);
            }
            if (windowWidth < MediaSize.xl) {
                inBuiltfunctionality.mainCatActivateScroll();
            }
        },

        onResize: function() {
            $(window).on('resize', function(event) {
                event.preventDefault();
                var windowWidth = window.innerWidth;
                if (windowWidth < MediaSize.xl) {
                    inBuiltfunctionality.mainCatActivateScroll();
                }
            });
        }
    };

    var _desktopResolution = {
        onRefresh: function() {
            var windowWidth = window.innerWidth;
            if (windowWidth > MediaSize.md) {
                toggleFunction.sidebar(true);
            }
        },

        onResize: function() {
            $(window).on('resize', function(event) {
                event.preventDefault();
                var windowWidth = window.innerWidth;
                if (windowWidth > MediaSize.md) {
                    // Add desktop-specific functionality if needed
                }
            });
        }
    };

    function sidebarFunctionality() {
        function sidebarCloser() {
            if (window.innerWidth <= 991) {
                if (!$('body').hasClass('alt-menu')) {
                    $('.main-container').removeClass('sbar-open');
                    $("#container").addClass("sidebar-closed");
                } else {
                    $(".navbar").removeClass("expand-header");
                    $('#container').removeClass('sbar-open');
                    $('html, body').removeClass('sidebar-noneoverflow');
                }
            } else if (window.innerWidth > 991) {
                if (!$('body').hasClass('alt-menu')) {
                    $('.sidebar-wrapper [aria-expanded="true"]').parents('li.menu').find('.collapse').addClass('show');
                } else {
                    $('html, body').addClass('sidebar-noneoverflow');
                    $("#container").addClass("sidebar-closed");
                    $(".navbar").addClass("expand-header");
                    $('#container').addClass('sbar-open');
                    $('.sidebar-wrapper [aria-expanded="true"]').parents('li.menu').find('.collapse').removeClass('show');
                }
            }
        }

        function sidebarMobCheck() {
            if (window.innerWidth <= 991) {
                if (!$('.main-container').hasClass('sbar-open') && !$('#compact_submenuSidebar').hasClass('show')) {
                    sidebarCloser();
                }
            } else {
                sidebarCloser();
            }
        }

        sidebarCloser();

        $(window).resize(function() {
            sidebarMobCheck();
        });
    }

    return {
        init: function() {
            toggleFunction.overlay();
            toggleFunction.search();
            toggleFunction.navbarShadow();

            _desktopResolution.onRefresh();
            _desktopResolution.onResize();

            _mobileResolution.onRefresh();
            _mobileResolution.onResize();

            sidebarFunctionality();

            inBuiltfunctionality.subCatScroll();
            inBuiltfunctionality.preventScrollBody();
            inBuiltfunctionality.languageDropdown();
            inBuiltfunctionality.onSidebarClick();

            // productionFunctionality.createButtons();
            // productionFunctionality.scrollTop();
            // productionFunctionality.checkScrollPosition();
        }
    }
}();
