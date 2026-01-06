// document.addEventListener('DOMContentLoaded', function () {
//   var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
//   tooltipTriggerList.forEach(function (el) {
//     new bootstrap.Tooltip(el, {
//       delay: { "show": 100, "hide": 50 } // adjust here
//     });
//   });
// });

// function isMobileDevice() {
//     return /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
// }

// if (isMobileDevice()) {
// 	$('li.menu_toggle_btns').removeClass('collapse expand');
// 	$('[data-bs-toggle="tooltip"]').tooltip('dispose').removeAttr('data-bs-toggle title');
// 	//$("#compactSidebar ul.menu-categories").removeClass('expand');
// 	$("#compactSidebar.new-menu").removeClass('min');
// 	$("#compactSidebar.new-menu").addClass('max');
// 	//$("#container.main-container").addClass('sidebar-closed');
// 	//$("#content.main-content").removeClass('expand');
//     //console.log("Mobile device");
// } else {
// 	//$("#compactSidebar ul.menu-categories").addClass('expand');
// 	//$("#compactSidebar.new-menu").removeClass('max');
//     //$("#compactSidebar.new-menu").addClass('min');
// 	//$("#container.main-container").addClass('sidebar-open');
// 	//$("#content.main-content,").addClass('expand');
// }

// $('li.main-menu a').click(function(e){
// 	if(!isMobileDevice()){
// 		if($('li.menu_toggle_btns').hasClass('collapse')){
// 			menutoggle(false);
// 		}
// 	}
// 	submenutoggle($(this));
// });

// $(".cstm_menu_toggle").click(function(){
// 	menutoggle(true);
// })


// function menutoggle(button){
// 	if(!isMobileDevice()){
// 		const element = $(".cstm_menu_toggle");
// 		element.closest('li.menu_toggle_btns').toggleClass('collapse expand');
// 		$(document).find('nav#compactSidebar').toggleClass('min max');
// 		$("#content.main-content").toggleClass('expand');
// 		if($('.main-menu.has-sub').hasClass('show')){
// 			$('.main-menu.has-sub').removeClass('show');
// 		}
// 		if(button && $(document).find('nav#compactSidebar').hasClass('max')){
// 			$('.main-menu.has-sub.active').addClass('show');
// 		}
// 	}
// }


// function submenutoggle(element){
// 	let main = element.parent('li');
// 	let top_ul = main.parent('ul.sub-menu');
// 	let top_parent = '';
// 	if(top_ul.length > 0){
// 		top_parent = top_ul.closest('li.main-menu');
// 	}
// 	$('.main-menu').not(main).not(top_parent).removeClass('show');
// 	if(main.hasClass('main-menu')){
// 		main.toggleClass('show');
// 	}else{
// 		element.addClass('active');
// 	}
// }



$('li.main-menu a').click(function(e){
    let main = $(this).parent('li');
    let top_ul = main.parent('ul.sub-menu');
    let top_parent = '';
    if(top_ul.length > 0){
        top_parent = top_ul.closest('li.main-menu');
    }
    $('.main-menu').not(main).not(top_parent).removeClass('show');
    if(main.hasClass('main-menu')){
        main.toggleClass('show');
    }else{
        $(this).addClass('active');
    }
});
$('nav#compactSidebar>ul>li.main-menu>a').click(function(){
    //alert($('li.menu_toggle_btns').hasClass('collapse'));
    if($('li.menu_toggle_btns').hasClass('collapse')){
        $("#cstm_menu_expande").click();
    }
});