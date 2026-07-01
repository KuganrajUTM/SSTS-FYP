<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>SSTS - EduTransit</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @yield('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
    <script src="https://unpkg.com/@laravel/echo@1.15.3/dist/echo.js"></script>

    <style>
        :root {
            --emerald:    #00b894;
            --emerald-dk: #007a63;
            --emerald-lt: #e6f9f5;
            --navy:       #0a1628;
            --slate:      #4a5568;
            --bg:         #f5f7fa;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--bg) !important;
        }

        /* Ambient Theme Elements */
        .glow-tr {
            position: fixed; width: 600px; height: 600px;
            top: -150px; right: -150px;
            background: radial-gradient(circle, rgba(0,184,148,0.08) 0%, transparent 70%);
            pointer-events: none; z-index: -1;
        }

        /* Animated Road Strip at bottom */
        .road-strip {
            position: fixed; bottom: 0; left: 0; right: 0;
            height: 6px; z-index: 1050; pointer-events: none;
            background: var(--emerald);
            box-shadow: 0 -2px 10px rgba(0,184,148,0.2);
        }

        #layoutSidenav_nav { background-color: var(--navy) !important; }
        
        .sb-sidenav-dark { background-color: var(--navy) !important; }
        
        .sb-sidenav-dark .sb-sidenav-menu .nav-link .sb-nav-link-icon {
            color: var(--emerald);
        }

        /* Content Headers */
        h1, .breadcrumb-item.active {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            color: var(--navy);
        }

        /* Mobile sidebar overlay */
        @media (max-width: 991.98px) {
            #layoutSidenav #layoutSidenav_nav {
                transform: translateX(-225px);
                position: fixed;
                top: 0; bottom: 0; left: 0;
                width: 225px;
                z-index: 1038;
                transition: transform 0.3s ease;
            }
            .sb-sidenav-toggled #layoutSidenav #layoutSidenav_nav {
                transform: translateX(0);
            }
            .sb-sidenav-toggled #layoutSidenav_content::after {
                content: '';
                display: block;
                position: fixed;
                top: 0; right: 0; bottom: 0; left: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1037;
            }
            #layoutSidenav_content {
                margin-left: 0 !important;
            }
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <div class="glow-tr"></div>
    <div class="road-strip"></div>

    @include('layout.header')

    <div id="layoutSidenav">
        @include('layout.nav-menu')

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    @yield('force-theme')
                    @yield('content')
                </div>
            </main>
            @include('layout.footer')
        </div>
    </div>

    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/scripts.js') }}"></script>
    @yield('scripts')
</body>
</html>