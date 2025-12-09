<?php
// resources/views/components/table.controls.view.php
$uid = 'tablectrl_' . bin2hex(random_bytes(5));
$class = isset($class) ? $class : '';
$columns = isset($columns) ? $columns : null;
$filters = isset($filters) ? $filters : null;

?>
<div id="{{ $uid }}" class="table-controls {{ $class }}">

  <div class="table-controls__left">
    @if (!empty($slots['search']))
    {{ $slots['search'] }}
    @else

    <form id="search-form" method="GET" action="{{ $action }}">

      <div class="tc-search">
        <c-input name="search" type="text" placeholder="Search">
        </c-input>

        <c-button type="submit" size="sm" form="search-form">
          <img src="{{ asset('assets/icons/search.svg') }}" />
        </c-button>
      </div>
    </form>
    @endif

    <div class="tc-filters">
      @if (!empty($filters) && is_array($filters))
      @foreach ($filters as $filterName => $filterItems)
      <div class="tc-actions column-dropdown">
        <c-dropdown.main :closeOnSelect="false">
          <c-slot name="trigger">
            <c-button variant="outline" class="dropdown-trigger">
              <img src="{{ asset('assets/icons/filter.svg') }}" />
              <span>{{ $filterName }}</span>
            </c-button>
          </c-slot>

          <c-slot name="menu">
            <form id="filter-form-{{$filterName}}" method="GET" action="{{$action}}">
              @foreach ($filterItems as $item)
              <c-dropdown.item>
                <label style="display: flex; align-items:center; gap:.5rem;">
                  <input type="checkbox" name="filters[{{ $filterName }}][]" value="{{ $item }}" />
                  <span>{{ $item }}</span>
                </label>
              </c-dropdown.item>
              @endforeach

              <c-button class="filter-btn" type="submit" variant="primary" size="sm" form="filter-form-{{$filterName}}">
                Apply
              </c-button>
            </form>
          </c-slot>
        </c-dropdown.main>
      </div>
      @endforeach
      @endif
      <a href="{{$action}}">
        <c-button variant="primary">
          Reset
        </c-button>
      </a>
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
      <c-dropdown.main :closeOnSelect="false">
        <c-slot name="trigger">
          <c-button variant="outline" class="dropdown-trigger">
            <img src="{{ asset('assets/icons/arrow-down-01-round.svg') }}" alt="" />
            <span>Columns</span>
          </c-button>
        </c-slot>

        <c-slot name="menu">
          <form id="col-form" method="GET" action="{{$action}}">
            @foreach ($columns as $col)
            <c-dropdown.item>
              <label style="display:inline-flex; align-items:center; gap:.5rem; width:100%;">
                <input type="checkbox" name="col[]" value="{{ $col }}" /> <span>{{ $col }}</span>
              </label>
            </c-dropdown.item>
            @endforeach
            <c-button class="filter-btn" type="submit" variant="primary" size="sm" form="col-form">
              Apply
            </c-button>
          </form>
        </c-slot>
      </c-dropdown.main>
    </div>
    @endif
  </div>
</div>