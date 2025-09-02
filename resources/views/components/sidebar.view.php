<?php
$role = $role ?? 'admin';
$class = $class ?? '';
$slots = $slots ?? [];

$menuItems = [
    'admin' => [
        'Main' => [
            [
                'name' => 'Dashboard',
                'link' => '#',
                'icon' => asset('/assets/icons/dashboard-square-02.svg'),

            ],
            [
                'name' => 'User Management',
                'link' => '#',
                'icon' => asset('/assets/icons/user.svg'),

                'children' => [
                    ['name' => 'Overview', 'link' => '#'],
                    ['name' => 'Admin', 'link' => '#'],
                ]
            ],
            [
                'name' => 'Child Profiles',
                'link' => '#',
                'icon' => asset('/assets/icons/baby-01.svg'),

                'children' => [
                    ['name' => 'Overview', 'link' => '#'],
                    ['name' => 'Linkage Requests', 'link' => '#'],
                    ['name' => 'Access Requests', 'link' => '#'],
                ]
            ],
            [
                'name' => 'Maternal Profiles',
                'link' => '#',
                'icon' => asset('/assets/icons/mother.svg'),

                'children' => [
                    ['name' => 'Overview', 'link' => '#'],
                    ['name' => 'Access Requests', 'link' => '#'],
                ]
            ],
            [
                'name' => 'Role & Permissions',
                'link' => '#',
                'icon' => asset('/assets/icons/user-unlock-01.svg'),
            ],
            [
                'name' => 'Vaccination',
                'link' => '#',
                'icon' => asset('/assets/icons/vaccine.svg'),
                'children' => [
                    ['name' => 'Vaccines', 'link' => '#'],
                    ['name' => 'Schedule', 'link' => '#'],
                ]
            ],
            [
                'name' => 'Appointments',
                'link' => '#',
                'icon' => asset('/assets/icons/profile.svg'),
            ],
            [
                'name' => 'Events & Campaigns',
                'link' => '#',
                'icon' => asset('/assets/icons/megaphone-02.svg'),
            ],
            [
                'name' => 'Communication',
                'link' => '#',
                'icon' => asset('/assets/icons/user-unlock-01.svg'),
            ],
        ],
        'Settings' => [
            [
                'name' => 'Notifications',
                'link' => '#',
                'icon' => asset('/assets/icons/notification-02.svg'),
            ],
            [
                'name' => 'System Configuration',
                'link' => '#',
                'icon' => asset('/assets/icons/configuration-02.svg'),
            ],
            [
                'name' => 'Settings',
                'link' => '#',
                'icon' => asset('/assets/icons/setting-07.svg'),
            ],
        ]
    ],
    'parent' => [
        'Main' => [
            [
                'name' => 'Dashboard',
                'link' => route('parent.dashboard'),
                'icon' => asset('/assets/icons/dashboard-square-02.svg'),

            ],
            [
                'name' => 'My Children',
                'link' => route('parent.my.children'),
                'icon' => asset('/assets/icons/baby-01.svg'),
               
            ],
            [
                'name' => 'Vaccination',
                'link' => route('parent.vaccination'),
                'icon' => asset('/assets/icons/vaccine.svg'),
               
            ],
            [
                'name' => 'Nutrition Tracking',
                'link' => route('parent.nutrition.tracking'),
                'icon' => asset('/assets/icons/chart-evaluation.svg'),
               
            ],
            [
                'name' => 'Appointments',
                'link' => route('parent.appointments'),
                'icon' => asset('/assets/icons/profile.svg'),
            ],
            [
                'name' => 'Events & Campaigns',
                'link' => route('parent.events.campaigns'),
                'icon' => asset('/assets/icons/megaphone-02.svg'),
            ],
             
        ],
        'Settings' => [
            [
                'name' => 'Notifications',
                'link' => route('parent.notifications'),
                'icon' => asset('/assets/icons/notification-02.svg'),
            ],
            [
                'name' => 'Settings',
                'link' => route('parent.settings'),
                'icon' => asset('/assets/icons/setting-07.svg'),
            ],
        ],],
    'guest' => [
        'Main' => [
            ['name' => 'Home', 'link' => '#'],
            ['name' => 'Profile', 'link' => '#'],
        ],
        'Settings' => [
            ['name' => 'Settings', 'link' => '#'],
        ]
    ],
];

$menus = $menuItems[$role] ?? $menuItems['admin'];
$currentPage = $_SERVER['REQUEST_URI'] ?? '/';
?>

<div class="sidebar <?= $class ?>">

    <div class="sidebar-header">
        <img src="{{asset('assets/logo.png')}}" alt="">
    </div>


    @foreach($menus as $section => $items)
    <div class="sidebar-section">
        <div class="sidebar-subtitle">{{ $section }}</div>
        @foreach ($items as $item)
            <div class="tab {{ $currentPage === $item['link'] ? 'active' : ''}} {{ !empty($item['children']) ? 'has-children' : '' }}">
                <a href="{{ $item['link'] }}" class="menu-link">
                    <img src="{{ asset($item['icon'] ?? '') }}" /> 
                    {{ $item['name'] }}
                    @if (!empty($item['children']))
                        <img src="{{ asset('assets/icons/arrow-down-01-round.svg') }}" class="arrow">
                    @endif
                </a>
                @if (!empty($item['children']))
                    <div class="submenu">
                        @foreach ($item['children'] as $child)
                            <a href="{{ $child['link'] }}"
                                class="submenu-link {{ $currentPage === $child['link'] ? 'active' : '' }}">
                                {{ $child['name'] }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    @endforeach
</div>

<script src="{{asset("js/components/sidebar.js")}}"></script>