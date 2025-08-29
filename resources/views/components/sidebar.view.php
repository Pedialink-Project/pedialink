<?php
$type = $type ?? 'admin';
$class = $class ?? '';
$slots = $slots ?? [];

$menuItems = [
    'admin' => [
        'Main' => [
            [
                'name' => 'Dashboard',
                'link' => '/admin/dashboard',
                'icon' => $icon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/icons/dashboard-square-02.svg'),

            ],
            [
                'name' => 'User Management',
                'link' => '/users',
                'icon' => $icon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/icons/user.svg'),

                'children' => [
                    ['name' => 'Overview', 'link' => '/users/overview'],
                    ['name' => 'Admin', 'link' => '/users/admin'],
                ]
            ],
            [
                'name' => 'Child Profiles',
                'link' => '/child-profiles',
                'icon' => $icon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/icons/baby-01.svg'),

                'children' => [
                    ['name' => 'Overview', 'link' => '/child/overview'],
                    ['name' => 'Linkage Requests', 'link' => '/child/linkage'],
                    ['name' => 'Access Requests', 'link' => '/child/access'],
                ]
            ],
            [
                'name' => 'Maternal Profiles',
                'link' => '/maternal-profiles',
                'icon' => $icon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/icons/mother.svg'),

                'children' => [
                    ['name' => 'Overview', 'link' => '/maternal/overview'],
                    ['name' => 'Access Requests', 'link' => '/maternal/access'],
                ]
            ],
            [
                'name' => 'Role & Permissions',
                'link' => '/roles',
                'icon' => $icon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/icons/user-unlock-01.svg'),
            ],
            [
                'name' => 'Vaccination',
                'link' => '/vaccinations',
                'icon' => $icon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/icons/vaccine.svg'),
                'children' => [
                    ['name' => 'Vaccines', 'link' => '/vaccine/overview'],
                    ['name' => 'Schedule', 'link' => '/vaccine/shedule'],
                ]
            ],
            [
                'name' => 'Appointments',
                'link' => '/appointments',
                'icon' => $icon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/icons/profile.svg'),
            ],
            [
                'name' => 'Events & Campaigns',
                'link' => '/events',
                'icon' => $icon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/icons/megaphone-02.svg'),
            ],
            [
                'name' => 'Communication',
                'link' => '/communication',
                'icon' => $icon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/icons/user-unlock-01.svg'),
            ],
        ],
        'Settings' => [
            [
                'name' => 'Notifications',
                'link' => '/notifications',
                'icon' => $icon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/icons/notification-02.svg'),
            ],
            [
                'name' => 'System Configuration',
                'link' => '/system-config',
                'icon' => $icon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/icons/configuration-02.svg'),
            ],
            [
                'name' => 'Settings',
                'link' => '/settings',
                'icon' => $icon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/icons/setting-07.svg'),
            ],
        ]
    ],
    'parent' => [
        'Main' => [
            ['name' => 'Dashboard', 'link' => '/dashboard'],
            [
                'name' => 'Posts',
                'link' => '/posts',
                'children' => [
                    ['name' => 'All Posts', 'link' => '/posts/all'],
                    ['name' => 'Create New', 'link' => '/posts/new'],
                ]
            ],
        ],
        'Settings' => [
            ['name' => 'Profile', 'link' => '/profile'],
            ['name' => 'Settings', 'link' => '/settings'],
        ]
    ],
    'guest' => [
        'Main' => [
            ['name' => 'Home', 'link' => '/home'],
            ['name' => 'Profile', 'link' => '/profile'],
        ],
        'Settings' => [
            ['name' => 'Settings', 'link' => '/settings'],
        ]
    ],
];

$menus = $menuItems[$type] ?? $menuItems['admin'];
$currentPage = $_SERVER['REQUEST_URI'] ?? '/';
?>

<div class="sidebar <?= $class ?>">

    <div class="sidebar-header">
        <img src="{{asset('assets/logo.png')}}" alt="">
    </div>


    @foreach($menus as $section => $items)
    <div class="sidebar-section">
        <div class="sidebar-subtitle"><?= $section ?></div>
        @foreach ($items as $item)
        <div
            class="tab <?= $currentPage === $item['link'] ? 'active' : '' ?> <?= !empty($item['children']) ? 'has-children' : '' ?>">
            <a href="<?= $item['link'] ?>" class="menu-link">
                <?= $item['icon'] ?? '' ?>
                {{$item['name'] }}
                @if (!empty($item['children']))
                <img src="{{asset('assets/icons/arrow-down-01-round.svg')}}" class="arrow">
                @endif
            </a>
            @if (!empty($item['children']))
            <div class="submenu">
                @foreach ($item['children'] as $child)
                <a href="<?= $child['link'] ?>"
                    class="submenu-link <?= $currentPage === $child['link'] ? 'active' : '' ?>">
                    {{$child['name']}}
                </a>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endforeach
</div>

<!-- <script>const menuTab = ".menu-link"

document.querySelectorAll(menuTab).forEach(link => {
    link.addEventListener("click", e => {
        const parent = link.closest(".tab");
        if (parent && parent.classList.contains("has-children")) {
            e.preventDefault();
            parent.classList.toggle("open");
        }
    });
});
</script> -->
<script src="{{asset("js/components/sidebar.js")}}"></script>