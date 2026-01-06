@props([
    'title' => '',
    'color'=>'#999',
    'icon' => 'fa-receipt',
    'iconColor' => '#ff3b30',
    'value' => '',
    'sub' => ''
])

<div class="billing-card d-flex flex-column align-items-center p-3"
     style="font-weight:400; font-style:italic; background:#fff; border-radius:16px;   border: 1px solid var(--light-border);
    text-align: center;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.03);">

    {{-- Icon Box --}}
    <div class="icon-box d-flex justify-content-center align-items-center mb-2"
         style="width:55px; height:55px; background:#f5f5f5; border-radius:12px;">
        <i class="fa {{ $icon }}" style="font-size:26px; color:{{ $iconColor }};"></i>
    </div>

    {{-- Title --}}
    <div class="billing-title mb-1" style="color:{{ @$color??'#999' }}; font-weight:600; font-style:italic;">
        {{ $title }}
    </div>

    {{-- Value --}}
    <div class="billing-value mb-1" style="color:#ff7a1a; font-weight:700; font-size:22px;">
        {{ $value }}
    </div>

    {{-- Subtext --}}
    <small class="muted" style="font-weight:500; font-style:italic; color:#444;">
        {{ $sub }}
    </small>

</div>
