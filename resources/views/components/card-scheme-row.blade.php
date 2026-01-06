{{-- resources/views/components/card-scheme-row.blade.php --}}
@props(['title'=>'','badge'=>'','meta'=>''])
<div class="col-md-4 col-12 p-2" >
    <a href="javascript:void(0)" 
    class="scheme-row d-flex justify-content-between align-items-center p-3 col-12 h-100"
    style="
        background:#fff; 
        border-radius:12px; 
        box-shadow:0 3px 10px rgba(0,0,0,0.08); 
        transition:0.2s ease; 
        cursor:pointer;
        text-decoration:none;
        color:inherit;
    "
    >
        <div>
            <div class="scheme-title" style="font-weight:600;">
                {{ $title }}
                @if($badge)
                    <span class="badge bg-light text-dark ms-2">{{ $badge }}</span>
                @endif
            </div>

            <small class="muted" style="font-style: italic;">
                {{ $meta }}
            </small>
        </div>

        <div class="scheme-arrow" style="color:#666; font-size:16px;">
            <i class="fa fa-arrow-right"></i>
        </div>
    </a>
</div>

