@props([
    'title' => '',
    'value' => '',
    'icon' => 'fa-award',
    'iconColor' => '#ff7a1a'
])

<div class="scheme-card d-flex flex-column align-items-center p-3"
     style="font-weight:400; font-style:italic; background:#fff; border-radius:16px;  border: 1px solid var(--light-border);
    text-align: center;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.03);">
    

    <!-- Icon Box -->
    <div class="icon-box d-flex justify-content-center align-items-center mb-2"
         style="width:55px; height:55px; background:#f5f5f5; border-radius:12px;">
        <i class="fa {{ $icon }}" style="font-size:26px; color:{{ $iconColor }};"></i>
    </div>

    <!-- Title -->
    <div class="scheme-title mb-1" style="color:#999; font-weight:600;">
        {{ $title }}
    </div>

    <!-- Value -->
    <div class="scheme-value mb-1" style="color:#ff7a1a; font-weight:700; font-size:22px;">
        {{ $value }}
    </div>

</div>
