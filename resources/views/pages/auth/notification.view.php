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
    <?php
        $notifications = [
            ["title" => "Notification title", "content" => "Lorem Ipsum Lorem Ipsum", "time" => "30 mins ago", "read" => true],
            ["title" => "Notification title", "content" => "Lorem Ipsum Lorem Ipsum", "time" => "30 mins ago", "read" => true],
            ["title" => "Notification title", "content" => "Lorem Ipsum Lorem Ipsum", "time" => "30 mins ago", "read" => false],
            ["title" => "Notification title", "content" => "Lorem Ipsum Lorem Ipsum", "time" => "30 mins ago", "read" => true],
            ["title" => "Notification title", "content" => "Lorem Ipsum Lorem Ipsum", "time" => "30 mins ago", "read" => false],
            ["title" => "Notification title", "content" => "Lorem Ipsum Lorem Ipsum", "time" => "30 mins ago", "read" => true],
            ["title" => "Notification title", "content" => "Lorem Ipsum Lorem Ipsum", "time" => "30 mins ago", "read" => false],
            ["title" => "Notification title", "content" => "Lorem Ipsum Lorem Ipsum", "time" => "30 mins ago", "read" => false],
            ["title" => "Notification title", "content" => "Lorem Ipsum Lorem Ipsum", "time" => "30 mins ago", "read" => true],
            ["title" => "Notification title", "content" => "Lorem Ipsum Lorem Ipsum", "time" => "30 mins ago", "read" => true],
        ];
    ?>

    <div class="notification-main">
        <div class="notification-btn-group">
            <c-button variant="secondary">
                Mark All As Read
            </c-button>
            <c-button variant="destructive">Clear All Notifications</c-button>
        </div>

        <div class="notification-card-group">

            @foreach ($notifications as $notification)
                <c-card class="notification-card {{ !$notification['read'] ? 'unread-card' : ''}}">
                    <c-slot name="header">
                        <div class="notification-title">
                            <h4>{{ $notification['title'] }}</h6>
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
                                <c-dropdown.item>
                                    Mark as Read
                                </c-dropdown.item>
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

                                    <c-slot name="close">
                                        Cancel
                                    </c-slot>

                                    <c-slot name="footer">
                                        <c-button variant="destructive">
                                            Delete
                                        </c-button>
                                    </c-slot>
                                </c-modal>
                            </c-slot>
                        </c-dropdown.main>
                    </c-slot>
                    <div class="notification-body">
                        {{ $notification['content'] }}
                    </div>
                </c-card>
            @endforeach
        </div>
    </div>
@endsection