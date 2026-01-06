{{-- resources/views/components/card-stock-item.blade.php --}}
@props([
    'title' => '',
    'value' => '',
    'icon' => 'fa-box',
    'low' => false,
    'iconColor' => '#ff3b30'
])

<div class="stock-card d-flex flex-column align-items-center p-3"
     style="font-weight:400; font-style:italic; background:#fff; border-radius:16px;   border: 1px solid var(--light-border);
    text-align: center;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.03);">

    <!-- Icon Box -->
    <div class="icon-box d-flex justify-content-center align-items-center mb-2"
         style="width:55px; height:55px; background:#f5f5f5; border-radius:12px;">
        <i class="fa {{ $icon }}" style="font-size:26px; color:{{ $iconColor }};"></i>
    </div>

    <!-- Title -->
    <div class="stock-title mb-1" style="color:#999; font-weight:600; font-style:italic;">
        {{ $title }}

        @if($low)
            <span class="badge" 
                  style="background:#ff3b30; color:white; font-size:10px; border-radius:6px; margin-left:6px;">
                Low Stock
            </span>
        @endif
    </div>

    <!-- Value -->
    <div class="stock-value mb-1" style="color:#ff7a1a; font-weight:700; font-size:22px;">
        {{ $value }}
    </div>

</div>
