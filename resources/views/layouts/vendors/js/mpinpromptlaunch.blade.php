<script>
	$(document).on('click', '[data-mpin-check="true"]',function(e){
		let element = $(e.target);
		let tag = element.prop('tagName').toLowerCase()??false;
		let mpincheck = element.data('mpin-check');
		if(mpincheck){
			e.preventDefault();
			let path = false;
			let form = false;
			let method = 'get';
			let form_data = false;
			let redirect = element.data('redirect')??false;
			switch(tag){
				case 'a':
					path = element.attr('href')??false;
					break;
				case 'button':
					form = element.closest('form')??false;
					if(form){
						path = form.attr('action')??false;
						method = form.attr('method')??'post';
						form_data = form.serialize();
					}else{
						path = element.data('url');
					}
					break;
				default:
					break;
			}
			
			if(path){
				let request_stream = "_token={{ csrf_token() }}&redirect="+redirect+'&method='+method;
				if(form_data){
					request_stream+="&data="+form_data;
				}
				window.mpinTriggerElement = element;
				$.get(path,request_stream, function(response){
					$('body').append(response);
				});
			}else{
				toastr.error("Path Not Found To Procedue !");
			}
		}
	});
</script>