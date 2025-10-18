<div class="view-list">
    @if (!empty($title))
        <div class="area-title">
            <span>{{ $title }}</span>
        </div>
    @endif
    
    
  @if(!empty($slots['list']))
    <div class="area-list">
        <ul>
            {{ $slots['list'] }}
        </ul>
    </div>
  @endif
</div>