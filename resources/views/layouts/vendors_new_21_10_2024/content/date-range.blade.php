<div class="form-group">
    <label>Day Wise</label>
    <div class="input-group">
    <button type = "button" class = "form-control float-right" id = "daterange-btn">
    <i class="far fa-calendar-alt"></i>
    @php

        $today_fl = date('Y/m/d') ;

        if(Auth::user()->can('view one week data')) {

            $today = new DateTime();
            $seventhDay = $today->sub(new DateInterval('P6D'))->format('Y/m/d') ;

        }else{

            $seventhDay = date('Y/m/d') ;

        }

        $date_range =  $seventhDay.' - '.$today_fl ;

        echo '<span  id="daterange-text"  >'. $date_range .'</span>' ;

    @endphp

    <i class="fas fa-caret-down"></i>
    </button>
    </div>
    <input type="hidden" class="form-control"  id = "reportrange" value = "{{$date_range}}"  readonly >
</div>
