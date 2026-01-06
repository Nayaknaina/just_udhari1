

<!-- jQuery -->
<script src="{{ asset('theme/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('theme/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('theme/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('theme/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('theme/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('theme/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('theme/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('theme/plugins/select2/js/select2.js')}}"></script>
<script src="{{ asset('theme/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('theme/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{ asset('theme/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{ asset('theme/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{ asset('theme/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('theme/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('theme/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

<!-- AdminLTE App -->

<script src = "{{ asset('theme/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<script src = "{{ asset('theme/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{ asset('theme/dist/js/adminlte.min.js?v=3.2.0')}}"></script>
<script src="{{ asset('theme/plugins/select2/js/select2.full.min.js')}}"></script>

<script>

    var url = window.location.href;

    // for sidebar menu entirely but not cover treeview
    $('ul.nav-sidebar a').filter(function() {
        return url.includes(this.href);
    }).addClass('active');

    // for treeview
    $('ul.nav-treeview a').filter(function() {
        return url.includes(this.href);
    }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active') ;

    $('.select2').select2() ;

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
