@extends('layout/portal')

@section('title')
Test Portal
@endsection

@section('header')
Test Table
@endsection

@section('content')

<form method="POST" action="{{ route('test.mail') }}">
    <c-button type="submit">Send mail</c-button>
</form>

<c-table.controls :columns='["ID","Name","Category","Date & Time","Status"]' action="{{ route('test.portal') }}" :filters="['Category' => ['Furniture','Footwear','Bedding'],'Status' => ['Active','Inactive','Draft']]">
    <c-slot name="extrabtn">
        <c-button variant="primary">
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
                    <c-table.th align="center">Price</c-table.th>
                    <c-table.th align="center">Stock</c-table.th>
                    <c-table.th class="table-actions"></c-table.th>
                </c-table.tr>
            </c-table.thead>

            <c-table.tbody>
                @foreach ($items as $key => $item)
                <c-table.tr>
                    <c-table.td col="id">{{ $item['id'] }}</c-table.td>
                    <c-table.td col="name">{{ $item['name'] }}</c-table.td>
                    <c-table.td col="category">{{ $item['category'] }}</c-table.td>
                    <c-table.td col="price" align="center">₨ {{ number_format($item['price'], 2) }}</c-table.td>
                    <c-table.td col="stock" align="center">{{ $item['stock'] }}</c-table.td>
                    <c-table.td class="table-actions" align="center">
                        <c-dropdown.main>
                            <c-slot name="trigger">
                                <c-button variant="ghost" class="dropdown-trigger">
                                    <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                </c-button>
                            </c-slot>
                            <c-slot name="menu">
                                <c-modal id="test-{{ $key }}" size="sm" :initOpen="false">
                                    <c-slot name="trigger">
                                        <c-dropdown.item>Export</c-dropdown.item>
                                    </c-slot>

                                    <c-slot name="header">
                                        <div>Event Details</div>
                                    </c-slot>

                                    <p>Here goes the modal body — images, form, whatever.</p>

                                    <c-slot name="footer">
                                        <c-button type="button" variant="outline" data-modal-close="eventDetails">Cancel</c-button>
                                        <c-button type="button" variant="primary" class="tc-btn tc-btn--primary" data-modal-confirm="eventDetails">Save</c-button>
                                    </c-slot>
                                </c-modal>

                                <c-dropdown.sep />
                                <c-dropdown.item>Export</c-dropdown.item>
                                <c-dropdown.item>Settings</c-dropdown.item>
                            </c-slot>
                        </c-dropdown.main>
                    </c-table.td>
                </c-table.tr>
                @endforeach
                @if(count($items) === 0)
                <tr>
                    <td colspan="6">
                        <div class="table-empty">No items found</div>
                    </td>
                </tr>
                @endif
            </c-table.tbody>
        </c-table.main>
    </div>
</c-table.wrapper>

<c-table.pagination />
@endsection