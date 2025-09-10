<div
  @if (!empty($id))
    id="{{ $id }}"
  @endif
  class="card {{ $class ?? '' }}"
>
  @if(!empty($slots['header']))
    <div class="card-header">
      <div class="card-title">{{ $slots['header'] }}</div>

      @if (isset($slots["headerSuffix"]))
        {{ $slots["headerSuffix"]}}
      @endif
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