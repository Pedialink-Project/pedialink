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

    <form data-merge-get id="search-form" method="GET" action="{{ $action }}">

      <div class="tc-search">
        <c-input name="search" type="text" placeholder="Search" value="{{ getQueryParam('search') ?? '' }}">
          <c-slot name="suffix">
            <c-button type="submit" size="sm" form="search-form">
              <img src="{{ asset('assets/icons/search.svg') }}" />
            </c-button>
          </c-slot>
        </c-input>
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
                <form data-merge-get id="filter-form-{{$filterName}}" method="GET" action="{{$action}}">
                  @foreach ($filterItems as $key => $item)
                    <c-dropdown.item asChild>
                      <c-checkbox
                        name="filters[{{ $filterName }}][]"
                        value="{{ $item }}"
                        label="{{ $item }}"
                        :checked="isFilterChecked('filters[' . $filterName . '][]', $item)"
                      />
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
        <a href="{{$action}}">
          <c-button variant="primary">
            Reset
          </c-button>
        </a>
      @endif
    </div>
  </div>

  <div class="table-controls__right">
    @if (!empty($slots['extrabtn']))
    <div class="tc-actions">
      {{ $slots['extrabtn'] }}
    </div>
    @endif
  </div>
</div>