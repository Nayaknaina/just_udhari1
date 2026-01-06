{{-- resources/views/components/card-stat.blade.php --}}
@props([
  'title' => '',
  'value' => '',
  'change' => null,
  'icon' => 'fa-chart',
  'actions' => null
])
<div class="card dash-card card-stat">
  <div class="card-body d-flex justify-content-between align-items-center">
    <div>
      <small class="muted">{{ $title }}</small>
      <h2 class="tile-number mb-1">{{ $value }}</h2>
      @if($change)
        <div class="muted small text-success">{{ $change }}</div>
      @endif
    </div>

    <div class="tile-actions text-end">
      @if($actions)
        {!! $actions !!}
      @else
        <div class="icon-box"><i class="fa {{ $icon }}"></i></div>
      @endif
    </div>
  </div>
</div>
