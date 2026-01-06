
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

    <script>

        var url = window.location.href ;

        $(document).ready(function() {

            $('.menu').click(function() {

                const table1 = document.querySelector("table") ;

                if(table1) {

                    var mytable = $('#CsTable').DataTable();
                    mytable.columns.adjust().draw() ;

                }

            });
        });

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

    </script>
