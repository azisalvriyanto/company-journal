<aside class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered bg-white  ">
    <div class="navbar-vertical-container">
        <div class="navbar-vertical-footer-offset">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('home') }}" aria-label="Sintas">
                <img class="navbar-brand-logo" src="{{ asset('assets/svg/logos/logo.svg') }}" alt="Logo" data-hs-theme-appearance="default">
                <img class="navbar-brand-logo" src="{{ asset('assets/svg/logos-light/logo.svg') }}" alt="Logo" data-hs-theme-appearance="dark">
                <img class="navbar-brand-logo-mini" src="{{ asset('assets/svg/logos/logo-short.svg') }}" alt="Logo" data-hs-theme-appearance="default">
                <img class="navbar-brand-logo-mini" src="{{ asset('assets/svg/logos-light/logo-short.svg') }}" alt="Logo" data-hs-theme-appearance="dark">
            </a>
            <!-- End Logo -->

            <!-- Navbar Vertical Toggle -->
            <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
                <i class="bi-arrow-bar-left navbar-toggler-short-align" data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-bs-toggle="tooltip" data-bs-placement="right" title="Collapse"></i>
                <i class="bi-arrow-bar-right navbar-toggler-full-align" data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-bs-toggle="tooltip" data-bs-placement="right" title="Expand"></i>
            </button>
            <!-- End Navbar Vertical Toggle -->

            <!-- Content -->
            <div class="navbar-vertical-content">
                <div id="navbarVerticalMenu" class="nav nav-pills nav-vertical card-navbar-nav">
                    <?php
                        $groups = [
                            'dashboard' => [
                                'name'          => NULL,
                                'permissions'   => [
                                    auth()->user()->hasRole('admin')
                                ],
                                'menus'         => [
                                    [
                                        'name'          => 'Dashboard',
                                        'icon'          => 'bi-house-door',
                                        'url'           => route('dashboards.index'),
                                        'route'         => 'dashboards.',
                                        'permissions'   => NULL,
                                        'sub_menus'     => NULL
                                    ]
                                ]
                            ],
                        ];

                        $groups['billing'] = [
                            'name'          => 'Billing',
                            'permissions'   => [
                                auth()->user()->hasRole('admin'),
                                auth()->user()->hasRole('user'),
                            ],
                            'menus'         => [
                                [
                                    'name'          => 'Billing',
                                    'icon'          => 'bi-bezier2',
                                    'url'           => route('billings.index'),
                                    'route'         => 'billings.',
                                    'permissions'   => [
                                        auth()->user()->hasRole('admin'),
                                        auth()->user()->hasRole('user'),
                                    ],
                                    'sub_menus'     => NULL
                                ],
                            ]
                        ];

                        $groups['purchase'] = [
                            'name'          => 'Purchase',
                            'permissions'   => [
                                auth()->user()->hasRole('admin'),
                                auth()->user()->hasRole('user'),
                            ],
                            'menus'         => [
                                [
                                    'name'          => 'Purchase Order',
                                    'icon'          => 'bi-bezier2',
                                    'url'           => route('purchase-orders.index'),
                                    'route'         => 'purchase-orders.',
                                    'permissions'   => [
                                        auth()->user()->hasRole('admin'),
                                        auth()->user()->hasRole('user'),
                                    ],
                                    'sub_menus'     => NULL
                                ],
                            ]
                        ];

                        $groups['invoice'] = [
                            'name'          => 'Invoice',
                            'permissions'   => [
                                auth()->user()->hasRole('admin'),
                                auth()->user()->hasRole('user'),
                            ],
                            'menus'         => [
                                [
                                    'name'          => 'Invoice',
                                    'icon'          => 'bi-bezier2',
                                    'url'           => route('invoices.index'),
                                    'route'         => 'invoices.',
                                    'permissions'   => [
                                        auth()->user()->hasRole('admin'),
                                        auth()->user()->hasRole('user'),
                                    ],
                                    'sub_menus'     => NULL
                                ],
                            ]
                        ];

                        $groups['sales'] = [
                            'name'          => 'Sales',
                            'permissions'   => [
                                auth()->user()->hasRole('admin'),
                                auth()->user()->hasRole('user'),
                            ],
                            'menus'         => [
                                [
                                    'name'          => 'Sales Order',
                                    'icon'          => 'bi-bezier2',
                                    'url'           => route('sales-orders.index'),
                                    'route'         => 'sales-orders.',
                                    'permissions'   => [
                                        auth()->user()->hasRole('admin'),
                                        auth()->user()->hasRole('user'),
                                    ],
                                    'sub_menus'     => NULL
                                ],
                            ]
                        ];

                        $groups['operating-cost-tarnsaction'] = [
                            'name'          => 'Operating Cost',
                            'permissions'   => [
                                auth()->user()->hasRole('admin'),
                                auth()->user()->hasRole('user'),
                            ],
                            'menus'         => [
                                [
                                    'name'          => 'Transaction',
                                    'icon'          => 'bi-bezier2',
                                    'url'           => route('operating-cost-transactions.index'),
                                    'route'         => 'operating-cost-transactions.',
                                    'permissions'   => [
                                        auth()->user()->hasRole('admin'),
                                        auth()->user()->hasRole('user'),
                                    ],
                                    'sub_menus'     => NULL
                                ],
                            ]
                        ];

                        $groups['reference'] = [
                            'name'          => 'Reference',
                            'permissions'   => [
                                auth()->user()->hasRole('admin'),
                                auth()->user()->hasRole('user'),
                            ],
                            'menus'         => [
                                [
                                    'name'          => 'Monthly Journal',
                                    'icon'          => 'bi-calendar4-range',
                                    'url'           => route('monthly-journals.index'),
                                    'route'         => 'monthly-journals.',
                                    'permissions'   => [
                                        auth()->user()->hasRole('admin'),
                                        auth()->user()->hasRole('user'),
                                    ],
                                    'sub_menus'     => NULL
                                ],
                                [
                                    'name'          => 'Storage Operation Type',
                                    'icon'          => 'bi-device-hdd',
                                    'url'           => route('storage-operation-types.index'),
                                    'route'         => 'storage-operation-types.',
                                    'permissions'   => [
                                        auth()->user()->hasRole('admin'),
                                        auth()->user()->hasRole('user'),
                                    ],
                                    'sub_menus'     => NULL
                                ],
                                [
                                    'name'          => 'Items',
                                    'icon'          => 'bi-kanban',
                                    'url'           => NULL,
                                    'route'         => 'items.',
                                    'permissions'   => [
                                        auth()->user()->hasRole('admin'),
                                        auth()->user()->hasRole('user'),
                                    ],
                                    'sub_menus'     => [
                                        [
                                            'name'          => 'Category',
                                            'url'           => route('items.categories.index'),
                                            'route'         => 'items.categories.',
                                            'permissions'   => [
                                                auth()->user()->hasRole('admin'),
                                                auth()->user()->hasRole('user'),
                                            ],
                                        ],
                                        [
                                            'name'          => 'Unit of Measurement',
                                            'url'           => route('items.unit-of-measurements.index'),
                                            'route'         => 'items.unit-of-measurements.',
                                            'permissions'   => [
                                                auth()->user()->hasRole('admin'),
                                                auth()->user()->hasRole('user'),
                                            ],
                                        ],
                                        [
                                            'name'          => 'Item',
                                            'url'           => route('items.items.index'),
                                            'route'         => 'items.items.',
                                            'permissions'   => [
                                                auth()->user()->hasRole('admin'),
                                                auth()->user()->hasRole('user'),
                                            ],
                                        ],
                                    ]
                                ],
                                [
                                    'name'          => 'Roles',
                                    'icon'          => 'bi-kanban',
                                    'url'           => route('laravelroles::roles.index'),
                                    'route'         => 'laravelroles::roles.',
                                    'permissions'   => [
                                        auth()->user()->hasRole('admin')
                                    ],
                                    'sub_menus'     => NULL
                                ],
                                [
                                    'name'          => 'Operating Cost',
                                    'icon'          => 'bi-cash-coin',
                                    'url'           => route('operating-costs.index'),
                                    'route'         => 'operating-costs.',
                                    'permissions'   => [
                                        auth()->user()->hasRole('admin'),
                                        auth()->user()->hasRole('user'),
                                    ],
                                    'sub_menus'     => NULL
                                ],
                                [
                                    'name'          => 'Payments',
                                    'icon'          => 'bi-credit-card',
                                    'url'           => NULL,
                                    'route'         => 'payments.',
                                    'permissions'   => [
                                        auth()->user()->hasRole('admin'),
                                        auth()->user()->hasRole('user'),
                                    ],
                                    'sub_menus'     => [
                                        [
                                            'name'          => 'Payment Term',
                                            'url'           => route('payments.payment-terms.index'),
                                            'route'         => 'payments.payment-terms.',
                                            'permissions'   => [
                                                auth()->user()->hasRole('admin'),
                                                auth()->user()->hasRole('user'),
                                            ],
                                        ],
                                        [
                                            'name'          => 'Payment Method',
                                            'url'           => route('payments.payment-methods.index'),
                                            'route'         => 'payments.payment-methods.',
                                            'permissions'   => [
                                                auth()->user()->hasRole('admin'),
                                                auth()->user()->hasRole('user'),
                                            ],
                                        ],
                                        [
                                            'name'          => 'Bank Account',
                                            'url'           => route('payments.bank-accounts.index'),
                                            'route'         => 'payments.bank-accounts.',
                                            'permissions'   => [
                                                auth()->user()->hasRole('admin'),
                                                auth()->user()->hasRole('user'),
                                            ],
                                        ],
                                    ]
                                ],
                                [
                                    'name'          => 'User',
                                    'icon'          => 'bi-people',
                                    'url'           => route('users.index'),
                                    'route'         => 'users.',
                                    'permissions'   => [
                                        auth()->user()->hasRole('admin'),
                                        auth()->user()->hasRole('user'),
                                    ],
                                    'sub_menus'     => NULL
                                ],
                            ]
                        ];

                        $groups['core'] = [
                            'name'          => 'Core',
                            'permissions'   => [
                                auth()->user()->hasRole('admin'),
                                auth()->user()->hasRole('user'),
                            ],
                            'menus'         => [
                                [
                                    'name'          => 'Status',
                                    'icon'          => 'bi-patch-check',
                                    'url'           => route('statuses.index'),
                                    'route'         => 'statuses.',
                                    'permissions'   => [
                                        auth()->user()->hasRole('admin'),
                                        auth()->user()->hasRole('user'),
                                    ],
                                    'sub_menus'     => NULL
                                ],
                                [
                                    'name'          => 'Owner Type',
                                    'icon'          => 'bi-diagram-3',
                                    'url'           => route('owner-types.index'),
                                    'route'         => 'owner-types.',
                                    'permissions'   => [
                                        auth()->user()->hasRole('admin'),
                                        auth()->user()->hasRole('user'),
                                    ],
                                    'sub_menus'     => NULL
                                ],
                                [
                                    'name'          => 'Operation Type',
                                    'icon'          => 'bi-wrench-adjustable-circle',
                                    'url'           => route('operation-types.index'),
                                    'route'         => 'operation-types.',
                                    'permissions'   => [
                                        auth()->user()->hasRole('admin'),
                                        auth()->user()->hasRole('user'),
                                    ],
                                    'sub_menus'     => NULL
                                ],
                                [
                                    'name'          => 'Bank',
                                    'icon'          => 'bi-patch-check',
                                    'url'           => route('banks.index'),
                                    'route'         => 'banks.',
                                    'permissions'   => [
                                        auth()->user()->hasRole('admin'),
                                        auth()->user()->hasRole('user'),
                                    ],
                                    'sub_menus'     => NULL
                                ],
                            ]
                        ];
                    ?>

                    @foreach($groups as $group)
                        @if($group['name'] && ($group['permissions'] == NULL || array_search(TRUE, $group['permissions']) !== false))
                        <span class="dropdown-header mt-4">{!! $group['name'] !!}</span>
                        <small class="bi-three-dots nav-subtitle-replacer"></small>
                        @endif

                        @foreach($group['menus'] as $menu)
                            @if($menu['permissions'] == NULL || array_search(TRUE, $menu['permissions']) !== false)
                                @if(!$menu['sub_menus'])
                                <div class="nav-item">
                                    <a class="nav-link {!! request()->routeIs($menu['route']. '*') ? 'active' : '' !!}" href="{!! $menu['url'] !!}" data-placement="left">
                                        <i class="nav-icon {!! $menu['icon'] !!}"></i>
                                        <span class="nav-link-title">{!! $menu['name'] !!}</span>
                                    </a>
                                </div>
                                @else
                                <div class="nav-item">
                                    <a class="nav-link dropdown-toggle {!! request()->routeIs($menu['route']. '*') ? 'active' : '' !!}" href="#navbarVerticalMenu{{ $loop->iteration }}" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenu{{ $loop->iteration }}" aria-expanded="{!! request()->routeIs($menu['route']. '*') ? 'true' : '' !!}" aria-controls="navbarVerticalMenu{{ $loop->iteration }}">
                                        <i class="nav-icon {!! $menu['icon'] !!}"></i>
                                        <span class="nav-link-title">{!! $menu['name'] !!}</span>
                                    </a>

                                    <div id="navbarVerticalMenu{{ $loop->iteration }}" class="nav-collapse collapse {!! request()->routeIs($menu['route']. '*') ? 'show' : '' !!}">
                                        @foreach($menu['sub_menus'] as $subMenu)
                                            @if($subMenu['permissions'] == NULL || array_search(TRUE, $subMenu['permissions']) !== false)
                                            <a class="nav-link {!! request()->routeIs($subMenu['route']. '*') ? 'active' : '' !!}" href="{!! $subMenu['url'] !!}">{!! $subMenu['name'] !!}</a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
            <!-- End Content -->

            <!-- Footer -->
            <div class="navbar-vertical-footer">
                <ul class="navbar-vertical-footer-list">
                    <li class="navbar-vertical-footer-list-item">
                        <!-- Style Switcher -->
                        <div class="dropdown dropup">
                            <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle" id="selectThemeDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-dropdown-animation>
                            </button>

                            <div class="dropdown-menu navbar-dropdown-menu navbar-dropdown-menu-borderless" aria-labelledby="selectThemeDropdown">
                                <a class="dropdown-item" href="#" data-icon="bi-moon-stars" data-value="auto">
                                    <i class="bi-moon-stars me-2"></i>
                                    <span class="text-truncate" title="Auto (system default)">Auto (system default)</span>
                                </a>
                                <a class="dropdown-item" href="#" data-icon="bi-brightness-high" data-value="default">
                                    <i class="bi-brightness-high me-2"></i>
                                    <span class="text-truncate" title="Default (light mode)">Default (light mode)</span>
                                </a>
                                <a class="dropdown-item" href="#" data-icon="bi-moon" data-value="dark">
                                    <i class="bi-moon me-2"></i>
                                    <span class="text-truncate" title="Dark">Dark</span>
                                </a>
                            </div>
                        </div>
                        <!-- End Style Switcher -->
                    </li>

                    <li class="navbar-vertical-footer-list-item">
                        <!-- Other Links -->
                        <div class="dropdown dropup">
                            <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle" id="otherLinksDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-dropdown-animation>
                                <i class="bi-info-circle"></i>
                            </button>

                            <div class="dropdown-menu navbar-dropdown-menu-borderless" aria-labelledby="otherLinksDropdown">
                                <a class="dropdown-item" href="javascript:;" data-bs-toggle="modal" data-bs-target="#welcomeMessageModal">
                                    <i class="bi-book dropdown-item-icon"></i>
                                    <span class="text-truncate" title="Welcome message">Welcome message</span>
                                </a>
                                <button type="button" class="dropdown-item" data-bs-toggle="offcanvas" data-bs-target="#offcanvasKeyboardShortcuts" aria-controls="offcanvasKeyboardShortcuts">
                                    <i class="bi-command dropdown-item-icon"></i>
                                    <span class="text-truncate" title="Keyboard shortcuts">Keyboard shortcuts</span>
                                </button>

                                <div class="dropdown-divider"></div>
                                <span class="dropdown-header">Contacts</span>
                                <a class="dropdown-item" href="http://tumbuhdanberproses.azisalvriyanto.net">
                                    <i class="bi-chat-left-dots dropdown-item-icon"></i>
                                    <span class="text-truncate" title="Contact support">Contact support</span>
                                </a>
                            </div>
                        </div>
                        <!-- End Other Links -->
                    </li>
                </ul>
            </div>
            <!-- End Footer -->
        </div>
    </div>
</aside>