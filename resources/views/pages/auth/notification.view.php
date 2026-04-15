@extends("layout/portal")

@section('title')
Notification
@endsection

@section('header')
<svg width="28" height="28" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M2.10831 12.308C1.9311 13.4697 2.72337 14.276 3.69342 14.6779C7.41238 16.2185 12.5877 16.2185 16.3067 14.6779C17.2767 14.276 18.069 13.4697 17.8918 12.308C17.7829 11.5941 17.2443 10.9996 16.8454 10.4191C16.3228 9.64941 16.2708 8.80988 16.2708 7.91669C16.2708 4.46491 13.4633 1.66669 10 1.66669C6.53681 1.66669 3.72931 4.46491 3.72931 7.91669C3.72924 8.80988 3.67731 9.64941 3.15471 10.4191C2.75574 10.9996 2.21722 11.5941 2.10831 12.308Z" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M6.66663 15.8333C7.0487 17.271 8.39624 18.3333 9.99996 18.3333C11.6037 18.3333 12.9512 17.271 13.3333 15.8333" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

Notification
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/auth/notification.css') }}">
@endsection

@section('content')


<div class="notification-main">
    @if(!empty($notifications))
    <div class="notification-btn-group">


        <c-modal id="mark-read-all-modal" size="sm">
            <c-slot name="trigger">
                <c-button variant="secondary">
                    Mark All As Read
                </c-button>
            </c-slot>

            <c-slot name="header">
                Mark All As Read
            </c-slot>

            <p>
                Do you want to mark all notifications as read?
            </p>
            <form method="POST" id="form-mark-as-all-read" action="{{ route( 'notification.mark.read.all') }}">
            </form>

            <c-slot name="close">
                Cancel
            </c-slot>

            <c-slot name="footer">
                <c-button variant="primary" type="submit" form="form-mark-as-all-read">
                    Mark as All Read
                </c-button>
            </c-slot>
        </c-modal>

        <c-modal id="delete-all-modal" size="sm">
            <c-slot name="trigger">
                <c-button variant="destructive">Clear All Notifications</c-button>

            </c-slot>

            <c-slot name="header">
                Delete Notification
            </c-slot>

            <p>
                Do you want to delete all notifications?
            </p>
            <form method="POST" id="form-delete-all" action="{{ route('notification.delete.all') }}">

            </form>

            <c-slot name="close">
                Cancel
            </c-slot>

            <c-slot name="footer">
                <c-button variant="destructive" type="submit" form="form-delete-all">
                    Delete All
                </c-button>
            </c-slot>
        </c-modal>
    </div>


    <div class="notification-card-group">

        @foreach ($notifications as $notification)
        <c-card class="notification-card {{ !$notification['is_read'] ? 'unread-card' : ''}}">
            <c-slot name="header">
                <div class="notification-title">
                    <h4>{{ $notification['title'] }}</h4>
                    <span class="notification-time">{{ $notification['time'] }}</span>
                </div>
            </c-slot>
            <c-slot name="headerSuffix">
                <c-dropdown.main>
                    <c-slot name="trigger">
                        <button class="options-btn" variant="ghost">
                            <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                        </button>
                    </c-slot>
                    <c-slot name="menu">
                        @if(!$notification['is_read'])
                        <c-modal>
                            <c-slot name="trigger">
                                <c-dropdown.item>
                                    Mark As Read
                                </c-dropdown.item>
                            </c-slot>

                            <c-slot name="header">
                                Mark As Read
                            </c-slot>

                            <p>
                                Do you want to mark this notification as read?
                            </p>
                            <form method="POST" id="form-mark-as-read-{{$notification['id']}}" action="{{ route('notification.mark.read', ['id' => $notification['id']]) }}">
                            </form>

                            <c-slot name="close">
                                Cancel
                            </c-slot>

                            <c-slot name="footer">
                                <c-button variant="primary" type="submit" form="form-mark-as-read-{{$notification['id']}}">
                                    Mark as Read
                                </c-button>
                            </c-slot>
                        </c-modal>
                        @endif
                        <c-modal>
                            <c-slot name="trigger">
                                <c-dropdown.item>
                                    Delete
                                </c-dropdown.item>
                            </c-slot>

                            <c-slot name="header">
                                Delete Notification
                            </c-slot>

                            <p>
                                Do you want to delete this notification?
                            </p>
                            <form method="POST" id="form-delete-{{$notification['id']}}" action="{{ route( 'notification.delete', ['id' => $notification['id']]) }}">

                            </form>

                            <c-slot name="close">
                                Cancel
                            </c-slot>

                            <c-slot name="footer">
                                <c-button variant="destructive" type="submit" form="form-delete-{{$notification['id']}}">
                                    Delete
                                </c-button>
                            </c-slot>
                        </c-modal>
                    </c-slot>
                </c-dropdown.main>
            </c-slot>
            <div class="notification-body">
                {{ $notification['message'] }}
            </div>
        </c-card>
        @endforeach
    </div>
    @else

    <c-emptytable
        alt="No Notifications"
        title="No Notifications"
        description="You have no notifications at this time." />
    @endif
</div>
@endsection