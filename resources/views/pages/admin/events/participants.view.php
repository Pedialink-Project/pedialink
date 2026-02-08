@extends('layout/portal')

@section('title')
Event Participants
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/admin/event-participants.css') }}">
@endsection

@section('header')


Event Management → Participants · EV-000{{ $id }}
@endsection


@section('content')

<c-table.controls action="{{route('admin.event.participants',['id' => $id])}}" :filters="['booking_status' => ['booked', 'cancelled']]">
</c-table.controls>

<c-table.wrapper card="1">
    <div class="table-wrapper" data-responsive="true">

        <c-table.main sticky="1" size="comfortable">

            <c-table.thead>
                <c-table.tr>
                    <c-table.th>Name</c-table.th>
                    <c-table.th>Email</c-table.th>
                    <c-table.th>Status</c-table.th>
                    <c-table.th>Registered At</c-table.th>
                    <c-table.th class="table-actions">Actions</c-table.th>
                </c-table.tr>
            </c-table.thead>


            <c-table.tbody>

                @foreach ($participants as $key => $participant)

                <c-table.tr>

                    <c-table.td>{{ $participant['name'] }}</c-table.td>
                    <c-table.td>{{ $participant['email'] }}</c-table.td>

                    <c-table.td>

                        @if($participant['booking_status'] === 'booked')
                        <c-badge type="green">Booked</c-badge>

                        @elseif($participant['booking_status'] === 'cancelled')
                        <c-badge type="red">Cancelled</c-badge>

                        @else
                        <c-badge type="purple">
                            {{ ucfirst($participant['booking_status']) }}
                        </c-badge>
                        @endif

                    </c-table.td>

                    <c-table.td>
                        {{ ($participant['registration_date']) }}
                    </c-table.td>


                    <c-table.td class="table-actions" align="center">

                        <c-dropdown.main>

                            <c-slot name="trigger">
                                <c-button variant="ghost">
                                    <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                </c-button>
                            </c-slot>


                            <c-slot name="menu">

                                <c-modal id="participant-{{ $key }}" size="sm" :initOpen="false">

                                    <c-slot name="trigger">
                                        <c-dropdown.item>View Details</c-dropdown.item>
                                    </c-slot>


                                    <c-slot name="header">
                                        Participant Details
                                    </c-slot>


                                    <c-modal.viewcard>

                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/profile.svg') }}"
                                            title="Participant Name"
                                            info="{{ $participant['name'] }}" />

                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/mail-01.svg') }}"
                                            title="Email"
                                            info="{{ $participant['email'] }}" />


                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/calendar-03.svg') }}"
                                            title="Registered At"
                                            info="{{ $participant['registration_date'] }}" />
                                        @if($participant['booking_status'] === 'cancelled')
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/calendar-03.svg') }}"
                                            title="Cancelled At"
                                            info="{{ $participant['cancelled_at'] }}" />
                                        @endif

                                    </c-modal.viewcard>




                                    @if($participant['booking_status'] === 'cancelled')

                                    <c-modal.viewlist title="Cancellation Reason">
                                        <c-slot name="list">
                                            <li>{{ $participant['cancel_reason'] ?? 'No reason provided' }}</li>
                                        </c-slot>
                                    </c-modal.viewlist>

                                    @endif


                                    <c-slot name="close">
                                        Close
                                    </c-slot>

                                </c-modal>

                            </c-slot>

                        </c-dropdown.main>

                    </c-table.td>

                </c-table.tr>

                @endforeach



                @if(count($participants) === 0)
                <tr>
                    <td colspan="6">
                        <div class="table-empty">No participants found</div>
                    </td>
                </tr>
                @endif


            </c-table.tbody>

        </c-table.main>

    </div>
</c-table.wrapper>

<c-table.pagination />

@endsection