@extends("layout/portal")

@section('title')
Notification
@endsection

@section('header')
Notification
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/auth/notification.css') }}">
@endsection

@section('content')


<div class="notification-main">
    <div class="notification-btn-group">
        <c-button variant="secondary">
            Mark All As Read
        </c-button>
        <c-button variant="destructive">Clear All Notifications</c-button>
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
                                <form method="POST" id="form-mark-as-read-{{$notification['id']}}" action="{{ route( auth()->user()->role . '.notification.mark.read', ['id' => $notification['id']]) }}">
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
                            <form method="POST" id="form-delete-{{$notification['id']}}" action="{{ route( auth()->user()->role . '.notification.delete', ['id' => $notification['id']]) }}">
                                
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
</div>
@endsection