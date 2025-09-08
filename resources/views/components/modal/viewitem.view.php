<div class="view-slot-item">
    @if (!empty($icon))
        <div class="slot-icon">
            <img src="{{ $icon }}" />
        </div>
    @endif
    
    <div class="slot-content">
        <span>{{ !empty($title) ? $title : '' }}</span>
        <span>{{ !empty($info) ? $info : '' }}</span>
    </div>
</div>