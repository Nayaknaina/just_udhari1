@props(['label'=>'','icon'=>'fa-wallet','value'=>'','sub'=>''])

<div class="ud-card d-flex flex-column align-items-center p-3" 
     style="font-weight:400; font-style:italic; background:#fff; border-radius:16px;">

    {{-- Icon Box Left --}}
    <div class="icon-box d-flex justify-content-center align-items-center mb-2"
         style="width:55px; height:55px; background:#f5f5f5; border-radius:12px;">
        <i class="fa {{ $icon }}" style="font-size:26px; color:#ff3b30;"></i>
    </div>

    {{-- Label (Top bold-italic) --}}
    <div class="ud-ico mb-1" style="color:#999; font-weight:600; font-style:italic;">
        {{ $label }}
    </div>

    {{-- Value --}}
    <div class="ud-val mb-1" style="color:#ff7a1a; font-weight:700; font-size:22px;">
        {{ $value }}
    </div>

    {{-- Subtext --}}
    <small class="muted" style="font-weight:500; font-style:italic; color:#444;">
        {{ $sub }}
    </small>
</div>
