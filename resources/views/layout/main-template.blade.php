<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>EduTransit</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>


    </head>

    <body class="sb-nav-fixed">

      @include('layout.header')

        <div id="layoutSidenav">

            @include('layout.nav-menu')

            <div id="layoutSidenav_content">

                <main>

                    <div class="container-fluid px-4">

                        @yield('content')

                    </div>

                </main>

                @include('layout.footer')

            </div>

        </div>

    </body>

</html>
