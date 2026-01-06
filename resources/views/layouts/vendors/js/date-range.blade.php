
<script src = "https://onetaperp.com/plugins/moment/moment.min.js"></script>
<!--<script src = "https://onetaperp.com/plugins/daterangepicker/daterangepicker.js"></script>-->

<script src = "{{ asset('main/assets/js/onetaperp_daterangepicker.js')}}"></script>

<script>

    $(document).ready(function () {
        // Get user permissions from the backend
        var userPermissions = @json(auth()->user()->getAllPermissions()->pluck('name'));

        // Define available ranges based on permissions
        var availableRanges = {};
        var today = moment();

        availableRanges['Today'] = [moment(), moment()] ;
        availableRanges['Yesterday'] = [moment().subtract(1, 'days'), moment().subtract(1, 'days')];

        if (userPermissions.includes('view all data')) {

            availableRanges['Last 7 Days'] = [moment().subtract(6, 'days'), moment()];
            availableRanges['Last 30 Days'] = [moment().subtract(29, 'days'), moment()];
            availableRanges['This Month'] = [moment().startOf('month'), moment().endOf('month')];
            availableRanges['Last Month'] = [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')] ;

        }else if (!userPermissions.includes('view one week data')) {

            availableRanges['Last 7 Days'] = [moment().subtract(6, 'days'), moment()];

        } else if (userPermissions.includes('view one month data')) {

            availableRanges['This Month'] = [moment().startOf('month'), moment().endOf('month')];

        }

        // Initialize the date range picker
        $('#daterange-btn').daterangepicker({
            ranges: availableRanges,
            startDate: userPermissions.includes('view one week data') && !userPermissions.includes('view one month data')
                ? moment().subtract(6, 'days')
                : moment(),
            endDate: moment(),
            alwaysShowCalendars: userPermissions.includes('view all data')
        },
        function (start, end) {

            var isValid = validateDateRangeWithPermissions(start, end, userPermissions);

            if (!isValid) {

                var today = moment();
                $('#reportrange').val(today.format('YYYY/MM/D') + ' - ' + today.format('YYYY/MM/D'));
                $('#daterange-text').html(today.format('YYYY/MM/D') + ' - ' + today.format('YYYY/MM/D'));

                $('#daterange-btn').data('daterangepicker').setStartDate(today);
                $('#daterange-btn').data('daterangepicker').setEndDate(today);

                changeEntries();

                return;

            }

            $('#reportrange').val(start.format('YYYY/MM/D') + ' - ' + end.format('YYYY/MM/D'));
            $('#daterange-text').html(start.format('YYYY/MM/D') + ' - ' + end.format('YYYY/MM/D'));

            changeEntries() ;

        });

        function validateDateRangeWithPermissions(start, end, userPermissions) {

            var diffDays = end.diff(start, 'days') + 1 ;

            // Get the start and end dates for the current week
            var startOfWeek = moment().startOf('week');
            var endOfWeek = moment().endOf('week');

            // Get the start and end dates for the current month
            var startOfMonth = moment().startOf('month');
            var endOfMonth = moment().endOf('month');

            if (userPermissions.includes('view one week data') && !userPermissions.includes('view one month data')) {
                if (start.isBefore(startOfWeek) || end.isAfter(endOfWeek)) {
                    toastr.error("You can only select dates within the current week.");
                    return false;
                }
            }

            if (userPermissions.includes('view one month data')) {
                if (start.isBefore(startOfMonth) || end.isAfter(endOfMonth)) {
                    toastr.error("You can only select dates within the current month.");
                    return false;
                }
            }

            // Validate for weekly data permission
            if ((diffDays > 1 && diffDays < 29) && !userPermissions.includes('view one week data')) {
                toastr.error("You do not have permission to view data for 1 week or more than .");
                return false;
            }

            // Validate for monthly data permission
            if ((diffDays === 30 || diffDays === 31) && !userPermissions.includes('view one month data')) {
                toastr.error("You do not have permission to view data for 1 month or more than.");
                return false;
            }

            // Validate for all data permission
            if (diffDays > 31 && !userPermissions.includes('view all data')) {
                toastr.error("You do not have permission to view data for more than 1 month or more than.");
                return false;
            }

            return true ;

        }

    });

</script>

