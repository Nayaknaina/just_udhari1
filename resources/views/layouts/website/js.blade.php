
    <script src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('theme/plugins/select2/js/select2.min.js') }}">
    <script src="{{ asset('assets/js/wow.min.js')}}"></script>
    <script src="{{ asset('assets/js/meanmenu.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.ajaxchimp.min.js')}}"></script>
    <script src="{{ asset('assets/js/form-validator.min.js')}}"></script>
    <script src="{{ asset('assets/js/contact-form-script.js')}}"></script>
    <script src="{{ asset('assets/js/custom.js')}}"></script>

    <script>

    function myFunction(elementId) {

        var element = document.getElementById(elementId);
        if (element.style.display === "none") {
            element.style.display = "block";
        } else {
            element.style.display = "none";

        }
    }

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