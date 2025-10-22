<?php
$role = $role ?? 'admin';
$class = $class ?? '';
$slots = $slots ?? [];

$menuItems = [
    'admin' => [
        'Main' => [
            [
                'name' => 'Dashboard',
                'link' => route('admin.dashboard'),
                'icon' => asset('/assets/icons/dashboard-square-02.svg'),

            ],
            [
                'name' => 'User Management',
                'link' => '#',
                'icon' => asset('/assets/icons/user.svg'),

                'children' => [
                    ['name' => 'Overview', 'link' => route('admin.user.overview')],
                    ['name' => 'Parent Account Approval', 'link' => route('admin.user.parent')],
                    ['name' => 'Admin', 'link' => route('admin.user.admin')],
                ]
            ],
            [
                'name' => 'Child Profiles',
                'link' => '#',
                'icon' => asset('/assets/icons/baby-01.svg'),

                'children' => [
                    ['name' => 'Overview', 'link' => route('admin.child.overview')],
                    ['name' => 'Linkage Requests', 'link' => route('admin.child.linkage.requests')],
                    ['name' => 'Access Requests', 'link' => route('admin.child.access.requests')],
                ]
            ],
            [
                'name' => 'Maternal Profiles',
                'link' => '#',
                'icon' => asset('/assets/icons/mother.svg'),

                'children' => [
                    ['name' => 'Overview', 'link' => route('admin.maternal.overview')],
                    ['name' => 'Access Requests', 'link' => route('admin.maternal.access.requests')],
                ]
            ],
            // [
            //     'name' => 'Role & Permissions',
            //     'link' => '#',
            //     'icon' => asset('/assets/icons/user-unlock-01.svg'),
            // ],
            [
                'name' => 'Vaccination',
                'link' => '#',
                'icon' => asset('/assets/icons/vaccine.svg'),
                'children' => [
                    ['name' => 'Vaccines', 'link' => route('admin.vaccination.vaccines')],
                    ['name' => 'Schedule', 'link' => route('admin.vaccination.schedule')],
                ]
            ],
            [
                'name' => 'Appointments',
                'link' => route('admin.appointment'),
                'icon' => asset('/assets/icons/profile.svg'),
            ],
            [
                'name' => 'Events & Campaigns',
                'link' => route('admin.event'),
                'icon' => asset('/assets/icons/megaphone-02.svg'),
            ],
            // [
            //     'name' => 'Communication',
            //     'link' => '#',
            //     'icon' => asset('/assets/icons/user-unlock-01.svg'),
            // ],
        ],
        'Settings' => [
            [
                'name' => 'Notifications',
                'link' => route('admin.notification'),
                'icon' => asset('/assets/icons/notification-02.svg'),
            ],
            // [
            //     'name' => 'System Configuration',
            //     'link' => '#',
            //     'icon' => asset('/assets/icons/configuration-02.svg'),
            // ],
            [
                'name' => 'Settings',
                'link' => route('admin.settings'),
                'icon' => asset('/assets/icons/setting-07.svg'),
            ],
        ]
    ],
    'phm' => [
        'Main' => [
            [
                'name' => 'Dashboard',
                'link' => route('phm.dashboard'),
                'icon' => asset('/assets/icons/dashboard-square-02.svg'),

            ],
            [
                'name' => 'Child Profiles',
                'link' => route('phm.child.profiles'),
                'icon' => asset('/assets/icons/baby-01.svg'),
               
            ],
            [
                'name' => 'Maternal Profiles',
                'link' => route('phm.maternal.profiles'),
                'icon' => asset('/assets/icons/mother.svg'),
               
            ],
            [
                'name' => 'Growth Monitoring',
                'link' => route('phm.growth.monitoring'),
                'icon' => asset('/assets/icons/chart-evaluation.svg'),
               
            ],
            [
                'name' => 'Vaccination',
                'link' => route('phm.vaccination'),
                'icon' => asset('/assets/icons/vaccine.svg'),
               
            ],
            // [
            //     'name' => 'Appointments',
            //     'link' => route('phm.appointments'),
            //     'icon' => asset('/assets/icons/profile.svg'),
            // ],
            [
                'name' => 'Appointments',
                'link' => '#',
                'icon' => asset('/assets/icons/profile.svg'),

                'children' => [
                    ['name' => 'My Appointments', 'link' => route('phm.appointments')],
                    ['name' => 'Request Appointments', 'link' => route('phm.appointments.requests')],
                ]
            ],
           
             
        ],
        'Settings' => [
            [
                'name' => 'Notifications',
                'link' => route('phm.notification'),
                'icon' => asset('/assets/icons/notification-02.svg'),
            ],
            [
                'name' => 'Settings',
                'link' => route('phm.settings'),
                'icon' => asset('/assets/icons/setting-07.svg'),
            ],
        ],],
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
                'name' => 'Growth Tracking',
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
                'link' => route('parent.notification'),
                'icon' => asset('/assets/icons/notification-02.svg'),
            ],
            [
                'name' => 'Settings',
                'link' => route('parent.settings'),
                'icon' => asset('/assets/icons/setting-07.svg'),
            ],
        ]
    ],
    'doctor' => [
        'Main' => [
            [
                'name' => 'Dashboard',
                'link' => route('doctor.dashboard'),
                'icon' => asset('/assets/icons/dashboard-square-02.svg'),

            ],
            [
                'name' => 'Child Profiles',
                'link' => route('doctor.child.profiles'),
                'icon' => asset('/assets/icons/baby-01.svg'),
               
            ],
            [
                'name' => 'Maternal Profiles',
                'link' => route('doctor.maternal.profiles'),
                'icon' => asset('/assets/icons/mother.svg'),
               
            ],
          
            [
                'name' => 'Appointments',
                'link' => route('doctor.appointments'),
                'icon' => asset('/assets/icons/profile.svg'),
            ],
           
             
        ],
        'Settings' => [
            [
                'name' => 'Notifications',
                'link' => route('doctor.notification'),
                'icon' => asset('/assets/icons/notification-02.svg'),
            ],
            [
                'name' => 'Settings',
                'link' => route('doctor.settings'),
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
        <a href="{{ route('home') }}"><img src="{{asset('assets/logo.png')}}" alt=""></a>
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