<header id="header" class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-container navbar-bordered bg-white">
    <div class="navbar-nav-wrap">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('home') }}" aria-label="Sintas">
            <img class="navbar-brand-logo" src="{{ asset('assets/svg/logos/logo.svg') }}" alt="Logo" data-hs-theme-appearance="default">
            <img class="navbar-brand-logo" src="{{ asset('assets/svg/logos-light/logo.svg') }}" alt="Logo" data-hs-theme-appearance="dark">
            <img class="navbar-brand-logo-mini" src="{{ asset('assets/svg/logos/logo-short.svg') }}" alt="Logo" data-hs-theme-appearance="default">
            <img class="navbar-brand-logo-mini" src="{{ asset('assets/svg/logos-light/logo-short.svg') }}" alt="Logo" data-hs-theme-appearance="dark">
        </a>
        <!-- End Logo -->

        <div class="navbar-nav-wrap-content-start">
            <!-- Navbar Vertical Toggle -->
            <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
                <i class="bi-arrow-bar-left navbar-toggler-short-align" data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-bs-toggle="tooltip" data-bs-placement="right" title="Collapse"></i>
                <i class="bi-arrow-bar-right navbar-toggler-full-align" data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-bs-toggle="tooltip" data-bs-placement="right" title="Expand"></i>
            </button>
            <!-- End Navbar Vertical Toggle -->

            <!-- Search Form -->
            <div class="dropdown ms-2">
                <!-- Input Group -->
                <div class="d-none d-lg-block">
                    <div class="input-group input-group-merge input-group-borderless input-group-hover-light navbar-input-group">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>

                        <input type="search" class="js-form-search form-control" placeholder="Search in App" aria-label="Search in App" data-hs-form-search-options='{
                            "clearIcon": "#clearSearchResultsIcon",
                            "toggleIconOnFocus": true,
                            "activeClass": "focus"
                        }'>
                        <a class="input-group-append input-group-text" href="javascript:;">
                            <i id="clearSearchResultsIcon" class="bi-x-lg" style="display: none;"></i>
                        </a>
                    </div>
                </div>

                <button class="js-form-search js-form-search-mobile-toggle btn btn-ghost-secondary btn-icon rounded-circle d-lg-none" type="button" data-hs-form-search-options='{
                    "clearIcon": "#clearSearchResultsIcon",
                    "toggleIconOnFocus": true,
                    "activeClass": "focus"
                }'>
                    <i class="bi-search"></i>
                </button>
                <!-- End Input Group -->
            </div>
            <!-- End Search Form -->
        </div>

        <div class="navbar-nav-wrap-content-end">
            <!-- Navbar -->
            @include('includes.navbar')
            <!-- End Navbar -->
        </div>
    </div>
</header>