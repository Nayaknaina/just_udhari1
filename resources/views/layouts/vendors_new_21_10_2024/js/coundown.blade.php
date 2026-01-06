
<script src = "{{ asset('theme/dist/js/coundown.js') }}" ></script>

<script>

    const subscriptions = @json($sub_arr) ;

    const clockDiv = document.getElementById('clock_div');

    subscriptions.forEach(subscription => {

        const card = createSubscriptionCard(subscription) ;
        clockDiv.appendChild(card) ;

        // Parse expiry_date and create a deadline
        const expiry_date = subscription.expiry_date ;
        // const deadline = new Date(Date.parse(exDate)) ;
        // const deadline = new Date(Date.parse(exDate) + 12 * 24 * 60 * 60 * 1000) ;

        var deadline = new Date(Date.parse(expiry_date)) ;
        // console.log(deadline) ;
        const countdownClock = new Clock(deadline, function () {
            console.log('Countdown ended'); // Callback when countdown ends
        }) ;

        document.getElementById(`countdown-container_${subscription.id}`).appendChild(countdownClock.el) ;

    }) ;

    if(subscriptions =='') {

        const Nocard = createNoSubscriptionCard() ;
        clockDiv.appendChild(Nocard) ;

    }

</script>
