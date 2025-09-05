<?php
$type = $type ?? 'admin';
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
            ['name' => 'Dashboard', 'link' => '/dashboard'],
            [
                'name' => 'Posts',
                'link' => '#',
                'children' => [
                    ['name' => 'All Posts', 'link' => '#'],
                    ['name' => 'Create New', 'link' => '#'],
                ]
            ],
        ],
        'Settings' => [
            ['name' => 'Profile', 'link' => '#'],
            ['name' => 'Settings', 'link' => '#'],
        ]
    ],
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

$menus = $menuItems[$type] ?? $menuItems['admin'];

function isCurrentParentItemOpen(array $item)
{
    if ($item['link'] === '#' && !empty($item['children'])) {
        foreach ($item['children'] as $child) {
            if ($child['link'] === route()->current()) {
                return true;
            }
        }

        return false;
    }
}
?>

<div class="sidebar <?= $class ?>">

    <div class="sidebar-header">
        <img src="{{asset('assets/logo.png')}}" alt="">
    </div>


    @foreach($menus as $section => $items)
    <div class="sidebar-section">
        <div class="sidebar-subtitle">{{ $section }}</div>
        @foreach ($items as $item)
            <div class="tab {{ isCurrentParentItemOpen($item) ? 'active open' : (route()->current() === $item['link'] ? 'active' : '') }} {{ !empty($item['children']) ? 'has-children' : '' }}">
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
                                class="submenu-link {{ route()->current() === $child['link'] ? 'active' : '' }}">
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