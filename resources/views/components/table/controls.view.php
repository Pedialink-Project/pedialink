<?php
// resources/views/components/table.controls.view.php
$uid = 'tablectrl_' . bin2hex(random_bytes(5));
$class = isset($class) ? $class : '';
$columns = isset($columns) ? $columns : null;
?>
<div id="{{ $uid }}" class="table-controls {{ $class }}">

  <div class="table-controls__left">
    @if (!empty($slots['search']))
      {{ $slots['search'] }}
    @else
        <c-input type="text" placeholder="Search">
            <c-slot name="prefix" >
                <span class="search-icon" aria-hidden="true">
                <img src="{{ asset('assets/icons/search.svg') }}" />
                </span>
            </c-slot>

        </c-input>
    @endif

    <div class="tc-filters">
        @if (!empty($slots['filter']))
        {{ $slots['filter'] }}
        @endif
    </div>
  </div>

  <div class="table-controls__right">
    @if (!empty($slots['extrabtn']))
      <div class="tc-actions">
        {{ $slots['extrabtn'] }}
      </div>
    @endif

    @if (!empty($columns) && is_array($columns))
      <div class="tc-actions column-dropdown">
        <c-dropdown.main>
          <c-slot name="trigger">
            <c-button variant="outline" class="dropdown-trigger">
                <img src="{{ asset('assets/icons/arrow-down-01-round.svg') }}" alt="" />
                <span>Columns</span>
            </c-button>
          </c-slot>

          <c-slot name="menu">
            @foreach ($columns as $col)
              <c-dropdown.item>
                <label style="display:inline-flex; align-items:center; gap:.5rem; width:100%;">
                  <input type="checkbox" name="col[]" value="{{ $col }}" /> <span>{{ $col }}</span>
                </label>
              </c-dropdown.item>
            @endforeach
          </c-slot>
        </c-dropdown.main>
      </div>
    @endif
  </div>
</div>
