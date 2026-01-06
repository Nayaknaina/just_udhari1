
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('main/assets/js/libs/jquery-3.1.1.min.js')}}"></script>
    <script src="{{ asset('main/bootstrap/js/popper.min.js')}}"></script>
    <script src="{{ asset('main/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('main/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{ asset('main/assets/js/app.js')}}"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="{{ asset('main/assets/js/custom.js')}}"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <script src="{{ asset('main/assets/js/dashboard/dash_1.js')}}"></script>
    <script src="{{ asset('main/assets/js/ju.js')}}"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

    <script src = "{{ asset('main/assets/js/scrollspyNav.js')}}"></script>
    <script src = "{{ asset('main/plugins/sweetalerts/sweetalert2.min.js')}}"></script>
    <script src = "{{ asset('main/plugins/sweetalerts/custom-sweetalert.js')}}"></script>
    <script src = "{{ asset('theme/plugins/toastr/toastr.min.js')}}"></script>
    <script src="{{ asset('theme/plugins/select2/js/select2.js')}}"></script>
    <script src="{{ asset('theme/plugins/select2/js/select2.full.min.js')}}"></script>

	<script src="{{ asset('main/assets/js/nowmenu.js')}}"></script>
    <script>

        var url = window.location.href ;

	 function datatableredraw(){
            const table1 = document.querySelector("table#CsTable") ;
                
            if(table1) {
                var mytable = $('#CsTable').DataTable();
                mytable.columns.adjust().draw() ;
            }
        }
        $(document).ready(function() {

            $('.menu').click(function() {

                datatableredraw();

            });
			
		 var formSubmitting = false;  // Flag to track if a form is being submitted
		 const process_wait = $('<div class="text-center" id="process_wait"><span class=""><i class="fa fa-spinner fa-spin"></i>Please Wait...</span></div>');

			$('form').on('keydown', function(e) {
                if (e.key === 'Enter' || e.keyCode === 13) {
                    e.preventDefault();
                }
            }); 

            // When a form is submitted, block Enter key
            $('form').on('submit', function(event) {
                formSubmitting = true;
                // Optionally, disable submit button to prevent multiple submissions
                $(this).find('button[type="submit"]').attr('disabled', true);
				 $(this).append(process_wait);
            });

            // Block Enter key if form is being submitted
            $(document).on('keydown', function(event) {
				if(event.key === "Enter" || event.keyCode === 13){
                    event.preventDefault();
                    return false;
                }
                /*if (formSubmitting && event.key === "Enter") {
                    event.preventDefault();  // Block Enter key press
                    toastr.error("Already Processing !");
                }*/
            });

            // Handle completion of any AJAX request
            $(document).on('ajaxComplete', function() {
                formSubmitting = false;  // Reset formSubmitting flag
                // Enable the submit button again
                $('form').find('button[type="submit"]').attr('disabled', false);
				$("#process_wait").remove();
            });

        });
		
		function cleardate(callback){
            $(document).on('click','.drp-buttons>.clearBtn',function(e){
                $('#daterange-text').html("Start Date - End Date");
                $('#reportrange').val("");
                if (typeof callback === "function") {
                    callback(); // Call the callback function passed as an argument
                }
            });
         }

        document.addEventListener('DOMContentLoaded', function () {

            const searchInput = document.querySelector('.search-form-control');
            const searchResults = document.getElementById('searchResults');

            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase();
                searchResults.innerHTML = '';  // Clear previous results

                if (query.trim() === '') {
                    return;  // Do nothing if the query is empty
                }

                const menuItems = document.querySelectorAll('#compact_submenuSidebar .submenu-list li a');
                const results = [] ;

                menuItems.forEach(function (item) {
                    if (item.textContent.toLowerCase().includes(query)) {
                        results.push({
                            text: item.textContent,
                            href: item.href
                        });
                    }
                });

                if (results.length > 0) {
                    results.forEach(result => {
                        const li = document.createElement('li');
                        li.classList.add('list-group-item');
                        const link = document.createElement('a');
                        link.href = result.href;
                        link.textContent = result.text;
                        li.appendChild(link);
                        searchResults.appendChild(li);
                    });
                } else {
                    searchResults.innerHTML = '<li class="list-group-item">No results found</li>';
                }

                searchResults.style.display = 'block';  // Show results
            });

            document.addEventListener('click', function (event) {
                // Check if the click target is outside the search input and results container
                if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
                    searchResults.style.display = 'none';  // Hide results
                }
            });

            // Optional: Hide results when input loses focus
            searchInput.addEventListener('blur', function () {
                setTimeout(function () {
                    searchResults.style.display = 'none';  // Hide results after input loses focus
                }, 200);  // Delay to allow clicking on the results
            });
        });

        $('.select2').select2() ;

        function nmFixed(num, fixed=3){

            var re = new RegExp('^-?\\d+(?:\.\\d{0,' + (fixed || -1) + '})?');
            return num.toString().match(re)[0] ;

        }

		function digitonly(event,num){
            let inputValue = event.target.value;

                // Allow only digits using regex
                inputValue = inputValue.replace(/[^0-9]/g, '');  // Remove anything that's not a digit

                // Ensure that the input has exactly 10 digits
                if (inputValue.length > num) {
                    inputValue = inputValue.slice(0, num);  // Trim to 10 digits
                }

                // Update the input field with the valid input
                event.target.value = inputValue;
        }
		
		function decimalonly(event,num = 2) {
            let el = event.target;
            let value = el.value;

            // Remove any invalid characters (allow only digits and one dot)
            value = value.replace(/[^0-9.]/g, '');
            let parts = value.split('.');
            if(parts.length > 2){
                value = value.slice(0,-1);
            }else{
                if(parts.length==2){
                    if(parts[0]==""){
                        value = '0.'+parts[1];
                    }else{
                        if(parts[1].length > num){
                            value = value.slice(0,-1);
                        }
                    }
                }
            }
            event.target.value = value;
        }
		

        
    </script>
