<ul class="navbar-nav">
    <li class="nav-item d-none d-sm-inline-block">
        <!-- Notification -->
        <div class="dropdown">
            <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle" id="navbarNotificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" data-bs-dropdown-animation hidden="">
                <i class="bi-bell"></i>
                <!-- <span class="btn-status btn-sm-status btn-status-danger"></span> -->
            </button>

            <div class="dropdown-menu dropdown-menu-end dropdown-card navbar-dropdown-menu navbar-dropdown-menu-borderless" aria-labelledby="navbarNotificationsDropdown" style="width: 25rem;">
                <div class="card">
                    <!-- Header -->
                    <div class="card-header card-header-content-between">
                        <h4 class="card-title mb-0">Notifications</h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body-height h-100">
                        <div class="tab-content" id="notificationTabContent">
                            <div class="tab-pane fade show active" id="notificationNavOne" role="tabpanel" aria-labelledby="notificationNavOne-tab">
                                <!-- List Group -->
                                <ul class="list-group list-group-flush">
                                    <!-- Item -->
                                    <li class="list-group-item form-check-select">
                                        <span class="text-muted">No notifications</span>
                                    </li>
                                    <!-- End Item -->

                                    <!-- Item -->
                                    <li class="list-group-item form-check-select" hidden="">
                                        <div class="row">
                                            <div class="col-auto">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="notificationCheck2" checked>
                                                        <label class="form-check-label" for="notificationCheck2"></label>
                                                        <span class="form-check-stretched-bg"></span>
                                                    </div>
                                                    <div class="avatar avatar-sm avatar-soft-dark avatar-circle">
                                                        <span class="avatar-initials">K</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col ms-n2">
                                                <h5 class="mb-1">Klara Hampton</h5>
                                                <p class="text-body fs-5">mentioned you in a comment</p>
                                                <blockquote class="blockquote blockquote-sm">Nice work, love! You really nailed it. Keep it up!</blockquote>
                                            </div>

                                            <small class="col-auto text-muted text-cap">10hr</small>
                                        </div>

                                        <a class="stretched-link" href="#"></a>
                                    </li>
                                    <!-- End Item -->
                                </ul>
                                <!-- End List Group -->
                            </div>
                        </div>
                    </div>
                    <!-- End Body -->

                    <!-- Card Footer -->
                    <a class="card-footer text-center" href="#">
                        View all notifications <i class="bi-chevron-right"></i>
                    </a>
                    <!-- End Card Footer -->
                </div>
            </div>
        </div>
        <!-- End Notification -->
    </li>

    <li class="nav-item d-none d-sm-inline-block">
        <!-- Activity -->
        <button class="btn btn-ghost-secondary btn-icon rounded-circle" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasActivityStream" aria-controls="offcanvasActivityStream" hidden="">
            <i class="bi-x-diamond"></i>
        </button>
        <!-- Activity -->
    </li>

    <li class="nav-item">
        <!-- Account -->
        <div class="dropdown">
            <a class="navbar-dropdown-account-wrapper" href="javascript:;" id="accountNavbarDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" data-bs-dropdown-animation>
                <div class="avatar avatar-sm avatar-circle">
                    <img class="avatar-img" src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : 'https://ui-avatars.com/api/?size=160&name=' . auth()->user()->name }}" alt="Image Description">
                    <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                </div>
            </a>

            <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless navbar-dropdown-account" aria-labelledby="accountNavbarDropdown" style="width: 16rem;">
                <div class="dropdown-item-text">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm avatar-circle">
                            <img class="avatar-img" src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : 'https://ui-avatars.com/api/?size=160&name=' . auth()->user()->name }}" alt="Image Description">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0">{{ auth()->user()->name }}</h5>
                            <p class="card-text text-body">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="#">Profile &amp; account</a>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
        <!-- End Account -->
    </li>
</ul>