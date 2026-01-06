
    function CountdownTracker(label, value){

        var el = document.createElement('span');

        el.className = 'flip-clock__piece';
        el.innerHTML = '<b class="flip-clock__card card"><b class="card__top"></b><b class="card__bottom"></b><b class="card__back"><b class="card__bottom"></b></b></b>' +
            '<span class="flip-clock__slot">' + label + '</span>';

        this.el = el ;

        var top = el.querySelector('.card__top'),
            bottom = el.querySelector('.card__bottom'),
            back = el.querySelector('.card__back'),
            backBottom = el.querySelector('.card__back .card__bottom');

        this.update = function(val){

            var val_pv = Math.abs(val) ;

            if(val_pv<10) { console.log(val_pv) ;}

            val =  (val_pv < 10) ?  '0' + val  : (''+val).slice(-3) ;

            if ( val !== this.currentValue ) {

            if ( this.currentValue >= 0 ) {
                back.setAttribute('data-value', this.currentValue);
                bottom.setAttribute('data-value', this.currentValue);
            }
            this.currentValue = val;
            top.innerText = this.currentValue;
            backBottom.setAttribute('data-value', this.currentValue);

            this.el.classList.remove('flip');
            void this.el.offsetWidth;
            this.el.classList.add('flip');
            }
        }

        this.update(value) ;

    }

    function getTimeRemaining(endtime) {

        var t = (Date.parse(endtime) - Date.parse(new Date())) / 1000;

        return {
            'Total': t,
            'Days': Math.floor(t / (86400)),
            'Hours': Math.floor((t / (3600)) % 24),
            'Minutes': Math.floor((t / 60) % 60),
            'Seconds': Math.floor((t ) % 60)
        };
    }

    function getTime() {
        var t = new Date();
        return {
            'Total': t,
            'Hours': t.getHours() % 12,
            'Minutes': t.getMinutes(),
            'Seconds': t.getSeconds()
        };
    }

    function Clock(countdown,callback) {

        countdown = countdown ? new Date(Date.parse(countdown)) : false;
        callback = callback || function(){};

        var updateFn = countdown ? getTimeRemaining : getTime;

        this.el = document.createElement('div');
        this.el.className = 'flip-clock';

        var trackers = {},
            t = updateFn(countdown),
            key, timeinterval;

        for ( key in t ){
            if ( key === 'Total' ) { continue; }
            trackers[key] = new CountdownTracker(key, t[key]);
            this.el.appendChild(trackers[key].el);
        }

        var i = 0 ;

        function updateClock() {
            timeinterval = requestAnimationFrame(updateClock);

            // throttle so it's not constantly updating the time.
            if ( i++ % 10 ) { return; }

            var t = updateFn(countdown);
            if ( t.Total < 0 ) {
            cancelAnimationFrame(timeinterval);
            for ( key in trackers ){
                trackers[key].update( 0 );
            }
            callback();
            return;
            }

            for ( key in trackers ){
            trackers[key].update( t[key] );
            }
        }

        setTimeout(updateClock,500) ;

    }

    function createSubscriptionCard(subscription) {

        // Create the main container div
        const colDiv = document.createElement('div');
        colDiv.className = 'col-lg-6';

        // Create the card container div
        const cardDiv = document.createElement('div');
        cardDiv.className = 'card card-body';

        // Create the heading element
        const heading = document.createElement('h3');
        heading.className = 'text-center';
        heading.textContent = subscription.title ;

        // Create the countdown container div
        const countdownContainer = document.createElement('div');
        countdownContainer.id = `countdown-container_${subscription.id}`;

        // Append elements to their respective parent containers
        cardDiv.appendChild(heading);
        cardDiv.appendChild(countdownContainer);
        colDiv.appendChild(cardDiv);

        return colDiv ;

    }

    function createNoSubscriptionCard() {

        // Create the main container div
        const colDiv = document.createElement('div');
        colDiv.className = 'col-lg-6';

        // Create the card container div
        const cardDiv = document.createElement('div');
        cardDiv.className = 'card card-body';

        // Create the heading element
        const heading = document.createElement('h3');
        heading.className = 'text-center';
        heading.textContent = 'No Subscription available' ;

        cardDiv.appendChild(heading);
        colDiv.appendChild(cardDiv);

        return colDiv ;

    }
