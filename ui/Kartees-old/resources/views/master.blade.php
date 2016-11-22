<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Sample 2</title>
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <link rel="shortcut icon" href="{{ URL::asset('assets/flat-ui/images/favicon.ico') }}">
        <link rel="stylesheet" href="{{ URL::asset('assets/flat-ui/bootstrap/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('assets/flat-ui/css/flat-ui.css') }}">
        <!-- Using only with Flat-UI (free)-->
        <link rel="stylesheet" href="{{ URL::asset('assets/common-files/css/icon-font.css') }}">
        <!-- end -->
        <link rel="stylesheet" href="{{ URL::asset('assets/css/style.css') }}">
    </head>

    <body>
        <div class="page-wrapper">
            <!-- header-2 -->
            
            @include('includes.header')
            
            
             @yield('content')

            <!-- logos -->
            <section class="logos">
                <div class="container">
                    <div><img src="assets/img/logos/generator.png" height="29" width="140" alt="Generator" /></div>
                    <div><img src="assets/img/logos/theGuardian.png" height="29" width="164" alt="TheGuardian" /></div>
                    <div><img src="assets/img/logos/forbes.png" height="29" width="93" alt="Forbes" /></div>
                    <div><img src="assets/img/logos/theNewYorkTimes.png" height="29" width="201" alt="TheNewYorkTimes" /></div>
                    <div><img src="assets/img/logos/tumblr.png" height="29" width="119" alt="Tumblr." /></div>
                </div>
            </section>

            <!-- footer-1 -->
            @include('includes.footer')
        </div>
        

        <!-- Placed at the end of the document so the pages load faster -->
        <script src="{{ URL::asset('assets/common-files/js/jquery-1.10.2.min.js') }}"></script>
        <script src="{{ URL::asset('assets/flat-ui/js/bootstrap.min.js') }}"></script>
        <script src="{{ URL::asset('assets/common-files/js/modernizr.custom.j') }}"></script>
        <script src="{{ URL::asset('assets/common-files/js/jquery.sharrre.min.js') }}"></script>
        <script src="{{ URL::asset('assets/common-files/js/startup-kit.js') }}"></script>
        <script src="{{ URL::asset('js/script.js') }}"></script>
    </body>
</html>