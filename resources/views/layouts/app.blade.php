<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>@yield('title', 'Tumbuh dan Berproses') | {{ config('app.name', 'Sintas App') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/font/bootstrap-icons.css') }}">

    <!-- CSS Front Template -->
    <link rel="preload" href="{{ asset('assets/css/theme.min.css') }}" data-hs-appearance="default" as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('assets/css/theme-dark.min.css') }}" data-hs-appearance="dark" as="style" onload="this.rel='stylesheet'">

    <!-- CSS Date Range Picker -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/daterangepicker/daterangepicker.css') }}">

    <!-- CSS Tom Select -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/tom-select/dist/css/tom-select.bootstrap5.css') }}">

    <!-- CSS jQuery Confirm -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-confirm/jquery-confirm.min.css') }}">

    <style data-hs-appearance-onload-styles>
        * {
            transition: unset !important;
        }

        body {
            opacity: 0;
        }
    </style>

    <script>
        window.hs_config = {
            "autopath":"@@autopath",
            "deleteLine":"hs-builder:delete",
            "deleteLine:build":"hs-builder:build-delete",
            "deleteLine:dist":"hs-builder:dist-delete",
            "previewMode":false,
            "startPath":"{{ route('home') }}",
            "vars":{
                "themeFont":"https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap",
                "version":"?v=1.0"
            },
            "layoutBuilder":{
                "extend":{
                    "switcherSupport":true
                },
                "header":{
                    "layoutMode":"default",
                    "containerMode":"container-fluid"
                },
                "sidebarLayout":"default"
            },
            "themeAppearance":{
                "layoutSkin":"default",
                "sidebarSkin":"default",
                "styles":{
                    "colors":{
                        "primary":"#377dff",
                        "transparent":"transparent",
                        "white":"#fff",
                        "dark":"132144",
                        "gray":{
                            "100":"#f9fafc",
                            "900":"#1e2022"
                        }
                    },
                    "font":"Inter"
                }
            },
            "languageDirection":{
                "lang":"en"
            },
            "skipFilesFromBundle":{
                "dist":[
                    "{{ asset('assets/js/hs.theme-appearance.js') }}",
                    "{{ asset('assets/js/hs.theme-appearance-charts.js') }}",
                    "{{ asset('assets/js/demo.js') }}"
                ],
                "build":[
                    "{{ asset('assets/css/theme.css') }}",
                    "{{ asset('assets/vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside-mini-cache.js') }}",
                    "{{ asset('assets/js/demo.js') }}",
                    "{{ asset('assets/css/theme-dark.css') }}",
                    "{{ asset('assets/css/docs.css') }}",
                    "{{ asset('assets/vendor/icon-set/style.css') }}",
                    "{{ asset('assets/js/hs.theme-appearance.js') }}",
                    "{{ asset('assets/js/hs.theme-appearance-charts.js') }}",
                    "node_modules/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js",
                    "{{ asset('assets/js/demo.js') }}"
                ]
            },
            "minifyCSSFiles":[
                "{{ asset('assets/css/theme.css') }}",
                "{{ asset('assets/css/theme-dark.css') }}"
            ],
            "copyDependencies":{
                "dist":{
                    "*assets/js/theme-custom.js":""
                },
                "build":{
                    "*assets/js/theme-custom.js":"",
                    "node_modules/bootstrap-icons/font/*fonts/**":"assets/css"
                }
            },
            "buildFolder":"",
            "replacePathsToCDN":{},
            "directoryNames":{
                "src":"./src",
                "dist":"./dist",
                "build":"./build"
            },
            "fileNames":{
                "dist":{
                    "js":"{{ asset('theme.min.js') }}",
                    "css":"{{ asset('theme.min.css') }}",
                },
                "build":{
                    "css":"{{ asset('theme.min.css') }}",
                    "js":"{{ asset('theme.min.js') }}",
                    "vendorCSS":"vendor.min.css",
                    "vendorJS":"vendor.min.js"
                }
            },
            "fileTypes":"jpg|png|svg|mp4|webm|ogv|json"
        }

        window.hs_config.gulpRGBA = (p1) => {
            const options = p1.split(',')
            const hex = options[0].toString()
            const transparent = options[1].toString()

            var c;
            if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
                c= hex.substring(1).split('');
                if(c.length== 3){
                c= [c[0], c[0], c[1], c[1], c[2], c[2]];
                }
                c= '0x'+c.join('');
                return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+',' + transparent + ')';
            }
            throw new Error('Bad Hex');
        }

        window.hs_config.gulpDarken = (p1) => {
            const options = p1.split(',')

            let col = options[0].toString()
            let amt = -parseInt(options[1])
            var usePound = false

            if (col[0] == "#") {
                col = col.slice(1)
                usePound = true
            }
            var num = parseInt(col, 16)
            var r = (num >> 16) + amt
            if (r > 255) {
                r = 255
            } else if (r < 0) {
                r = 0
            }

            var b = ((num >> 8) & 0x00FF) + amt
            if (b > 255) {
                b = 255
            } else if (b < 0) {
                b = 0
            }

            var g = (num & 0x0000FF) + amt
            if (g > 255) {
                g = 255
            } else if (g < 0) {
                g = 0
            }

            return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16)
        }

        window.hs_config.gulpLighten = (p1) => {
            const options = p1.split(',')

            let col = options[0].toString()
            let amt = parseInt(options[1])
            var usePound = false

            if (col[0] == "#") {
                col = col.slice(1)
                usePound = true
            }
            var num = parseInt(col, 16)
            var r = (num >> 16) + amt
            if (r > 255) {
                r = 255
            } else if (r < 0) {
                r = 0
            }
            var b = ((num >> 8) & 0x00FF) + amt
            if (b > 255) {
                b = 255
            } else if (b < 0) {
                b = 0
            }
            var g = (num & 0x0000FF) + amt
            if (g > 255) {
                g = 255
            } else if (g < 0) {
                g = 0
            }

            return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16)
        }
    </script>

    <style>
        .heart-svg {
            fill: red;
            position: relative;
            top: -1px;
            height: 6pt;
            animation: pulse 1s ease infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }

            100% {
                transform: scale(1);
            }
        }

        div.dataTables_wrapper div.dataTables_processing {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            margin-left: -50%;
            margin-top: 0px;
            padding-top: 20px;
            text-align: center;
            font-size: 1.2em;
        }
    </style>

    <!-- Optional Style -->
    @yield('style')
</head>

@if(auth()->check())
<body class="has-navbar-vertical-aside navbar-vertical-aside-show-xl footer-offset">
@else
<body class="d-flex align-items-center min-h-100">
@endif
    <script src="{{ asset('assets/js/hs.theme-appearance.js') }}"></script>
    <script src="{{ asset('assets/vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside-mini-cache.js') }}"></script>

    <!-- ========== HEADER ========== -->
    @if(auth()->check())
    @include('includes.header')
    @else
    @include('includes.auth.header')
    @endif
    <!-- ========== END HEADER ========== -->

    <!-- ========== MAIN CONTENT ========== -->
    @if(auth()->check())
    <!-- Navbar Vertical -->
    @include('includes.sidebar')
    <!-- End Navbar Vertical -->
    @endif

    <main id="content" role="main" class="main">
        <!-- Content -->
        @if(auth()->check())
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header pb-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h1 class="page-header-title">@yield('title', 'Tumbuh dan Berproses')</h1>

                        <div class="d-flex justify-content-start">
                            <!-- List Separator -->
                            <ul class="list-inline list-separator">
                                @yield('list-separator')
                            </ul>
                            <!-- End List Separator -->
                        </div>
                    </div>
                    <!-- End Col -->
                </div>
                <!-- End Row -->
            </div>
            <!-- End Page Header -->

            @yield('content')
        </div>
        @else
        <div class="container-fluid px-3">
            @yield('content')
        </div>
        @endif
        <!-- End Content -->

        @if(auth()->check())
        <!-- Footer -->
        @include('includes.footer')
        <!-- End Footer -->
        @endif
    </main>
    <!-- ========== END MAIN CONTENT ========== -->

    @if(auth()->check())
    <!-- ========== SECONDARY CONTENTS ========== -->
    <!-- Keyboard Shortcuts -->
    @include('includes.secondary-contents.keyboard-shortcuts')
    <!-- End Keyboard Shortcuts -->

    <!-- Activity -->
    @include('includes.secondary-contents.activity')
    <!-- End Activity -->

    <!-- Welcome Message Modal -->
    @include('includes.secondary-contents.welcome-message')
    <!-- End Welcome Message Modal -->

    <!-- Go to -->
    <a class="js-go-to go-to position-fixed" href="javascript:;" style="visibility: hidden;" data-hs-go-to-options='{
        "offsetTop": 200,
        "position": {
            "init": {
                "right": "2rem"
            },
            "show": {
                "bottom": "2rem"
            },
            "hide": {
                "bottom": "-2rem"
            }
        }
    }'>
        <i class="bi-chevron-up"></i>
    </a>
    <!-- End Go to -->
    <!-- ========== END SECONDARY CONTENTS ========== -->
    @endif

    <!-- JS Global Compulsory  -->
    <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-migrate/dist/jquery-migrate.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    <!-- JS Implementing Plugins -->
    <script src="{{ asset('assets/vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/hs-form-search/dist/hs-form-search.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/hs-go-to/dist/hs-go-to.min.js') }}"></script>

    <!-- JS Front -->
    <script src="{{ asset('assets/js/theme.min.js') }}"></script>
    <script src="{{ asset('assets/js/hs.theme-appearance-charts.js') }}"></script>

    <!-- CSS Tom Select -->
    <script src="{{ asset('assets/vendor/tom-select/dist/js/tom-select.complete.min.js') }}"></script>

    <!-- JS jQuery Confirm -->
    <script src="{{ asset('assets/vendor/jquery-confirm/jquery-confirm.min.js') }}"></script>

    <!-- JS Style Switcher -->
    <script>
        (function () {
            // INITIALIZATION OF NAVBAR VERTICAL ASIDE
            new HSSideNav('.js-navbar-vertical-aside').init();

            // INITIALIZATION OF FORM SEARCH
            const HSFormSearchInstance = new HSFormSearch('.js-form-search');

            if (HSFormSearchInstance.collection.length) {
                HSFormSearchInstance.getItem(1).on('close', function (el) {
                    el.classList.remove('top-0')
                });

                document.querySelector('.js-form-search-mobile-toggle').addEventListener('click', e => {
                    let dataOptions = JSON.parse(e.currentTarget.getAttribute('data-hs-form-search-options')),
                    $menu = document.querySelector(dataOptions.dropMenuElement)

                    $menu.classList.add('top-0')
                    $menu.style.left = 0
                });
            }

            // INITIALIZATION OF BOOTSTRAP DROPDOWN
            HSBsDropdown.init();

            // STYLE SWITCHER
            const $dropdownBtn = document.getElementById('selectThemeDropdown') // Dropdowon trigger
            const $variants = document.querySelectorAll(`[aria-labelledby="selectThemeDropdown"] [data-icon]`) // All items of the dropdown

            // Function to set active style in the dorpdown menu and set icon for dropdown trigger
            const setActiveStyle = function () {
                $variants.forEach($item => {
                    if ($item.getAttribute('data-value') === HSThemeAppearance.getOriginalAppearance()) {
                        $dropdownBtn.innerHTML = `<i class="${$item.getAttribute('data-icon')}" />`
                        return $item.classList.add('active');
                    }

                    $item.classList.remove('active');
                })
            }

            // Add a click event to all items of the dropdown to set the style
            $variants.forEach(function ($item) {
                $item.addEventListener('click', function () {
                    HSThemeAppearance.setAppearance($item.getAttribute('data-value'))
                });
            });

            // Call the setActiveStyle on load page
            setActiveStyle();

            // Add event listener on change style to call the setActiveStyle function
            window.addEventListener('on-hs-appearance-change', function () {
                setActiveStyle();
            });

            // INITIALIZATION OF GO TO
            new HSGoTo('.js-go-to');
        })();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <!-- End Style Switcher JS -->

    <!-- Optional JS -->
    @yield('javascript')
    <!-- End Optional JS -->
</body>
</html>