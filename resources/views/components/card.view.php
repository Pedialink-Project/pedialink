<div {{ !empty($id) ? "id={$id}" : "" }} class="card {{ $class ?? '' }}">
  @if(!empty($slots['header']))
    <div class="card-header">
      <div class="card-title">{{ $slots['header'] }}</div>
    </div>
  @endif

  <div class="card-body">
    {{ $slot ?? '' }}
  </div>

  @if(!empty($slots['footer']))
    <div class="card-footer">
      {{ $slots['footer'] }}
    </div>
  @endif
</div>