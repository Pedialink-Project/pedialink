@extends('layout/portal')

@section('title')
Test Portal
@endsection

@section('header')
    Test Table
@endsection

@section('content')
    <?php
    $items = [
        ['id' => 1, 'name' => 'Deluxe Chair',      'category' => 'Furniture', 'price' => 129.99, 'stock' => 12, 'status' => 'active',  'created_at' => '2025-01-03'],
        ['id' => 2, 'name' => 'Oak Desk',          'category' => 'Furniture', 'price' => 349.50, 'stock' => 5,  'status' => 'active',  'created_at' => '2024-11-21'],
        ['id' => 3, 'name' => 'Ceramic Mug (Set)', 'category' => 'Kitchen',   'price' => 19.95,  'stock' => 120,'status' => 'active',  'created_at' => '2025-03-10'],
        ['id' => 4, 'name' => 'LED Lamp',          'category' => 'Lighting',  'price' => 45.00,  'stock' => 0,  'status' => 'out-of-stock','created_at' => '2024-12-02'],
        ['id' => 5, 'name' => 'Bluetooth Speaker', 'category' => 'Audio',     'price' => 79.9,   'stock' => 34, 'status' => 'active',  'created_at' => '2025-02-18'],
        ['id' => 6, 'name' => 'Cotton Blanket',    'category' => 'Bedding',   'price' => 59.0,   'stock' => 8,  'status' => 'active',  'created_at' => '2024-10-09'],
        ['id' => 7, 'name' => 'Stainless Knife',   'category' => 'Kitchen',   'price' => 24.5,   'stock' => 200,'status' => 'active',  'created_at' => '2025-04-01'],
        ['id' => 8, 'name' => 'Wall Art - Blue',   'category' => 'Decor',     'price' => 89.0,   'stock' => 3,  'status' => 'active',  'created_at' => '2024-09-30'],
        ['id' => 9, 'name' => 'Running Shoes',     'category' => 'Footwear',  'price' => 99.99,  'stock' => 22, 'status' => 'active',  'created_at' => '2025-05-05'],
        ['id' =>10, 'name' => 'Notebook A5',       'category' => 'Stationery', 'price' => 6.5,    'stock' => 500,'status' => 'active',  'created_at' => '2025-06-12'],
        ['id' =>11, 'name' => 'Desk Plant',        'category' => 'Plants',    'price' => 15.0,   'stock' => 40, 'status' => 'active',  'created_at' => '2025-01-30'],
        ['id' =>12, 'name' => 'USB-C Cable',       'category' => 'Electronics','price' => 8.99,  'stock' => 150,'status' => 'active',  'created_at' => '2025-03-20'],
    ];
    ?>

    <c-table.controls :columns='["ID","Name","Category","Date & Time","Status"]'>

        <c-slot name="filter">
            <c-button variant="outline">
                <img src="{{ asset('assets/icons/filter.svg') }}" />
                Category
            </c-button>
            <c-button variant="outline">
                <img src="{{ asset('assets/icons/filter.svg') }}" />
                Status
            </c-button>
        </c-slot>

        <c-slot name="extrabtn">
            <c-button variant="primary" >
                Add Item
            </c-button>
        </c-slot>
    </c-table.controls>

    <c-table.wrapper card="1">
        <div class="table-wrapper" data-responsive="true">
            <c-table.main sticky="1" size="comfortable">
                <c-table.thead>
                    <c-table.tr>
                        <c-table.th width="70px">ID</c-table.th>
                        <c-table.th sortable="1">Name</c-table.th>
                        <c-table.th>Category</c-table.th>
                        <c-table.th align="right">Price</c-table.th>
                        <c-table.th align="center">Stock</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($items as $item)
                        <c-table.tr>
                            <c-table.td col="id">{{ $item['id'] }}</c-table.td>
                            <c-table.td col="name">{{ $item['name'] }}</c-table.td>
                            <c-table.td col="category">{{ $item['category'] }}</c-table.td>
                            <c-table.td col="price" align="right">₨ {{ number_format($item['price'], 2) }}</c-table.td>
                            <c-table.td col="stock" align="center">{{ $item['stock'] }}</c-table.td>
                            <c-table.td class="table-actions" align="center">
                                <c-dropdown.main>
                                    <c-slot name="trigger">
                                        <c-button variant="ghost" class="dropdown-trigger">
                                            <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                        </c-button>
                                    </c-slot>
                                    <c-slot name="menu">
                                        <c-dropdown.item>Export</c-dropdown.item>
                                        <c-dropdown.sep />
                                        <c-dropdown.item>Export</c-dropdown.item>
                                        <c-dropdown.item>Settings</c-dropdown.item>
                                    </c-slot>
                                </c-dropdown.main>
                            </c-table.td>
                        </c-table.tr>
                    @endforeach
                    @if(count($items) === 0)
                        <tr><td colspan="6"><div class="table-empty">No items found</div></td></tr>
                    @endif
                </c-table.tbody>
            </c-table.main>
        </div>
    </c-table.wrapper>

    <c-table.pagination />
@endsection