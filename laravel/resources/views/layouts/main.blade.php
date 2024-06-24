<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Renerconsys - PM Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <!-- ================== BEGIN core-css ================== -->
    <link href="{{ url('sites/css/vendor.min.css') }}" rel="stylesheet" />
    <link href="{{ url('sites/css/app2.min.css') }}" rel="stylesheet" />
    <!-- ================== END core-css ================== -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="{{url('sites/plugins/apexcharts/dist/apexcharts.min.js')}}"></script>
</head>

<body>
    <!-- BEGIN #app -->
    <div id="app" class="app app-content-full-width">
        <!-- BEGIN #header -->
        <div id="header" class="app-header">
            <!-- BEGIN desktop-toggler -->

            <!-- END mobile-toggler -->

            <!-- BEGIN brand -->
            <div class="brand">
                <a href="{{route('dashboard')}}" class="brand-logo">
                    <span class="brand-img">
                        <span class="brand-img-text text-theme"><b>C</b></span>

                    </span>
                    <span class="brand-img">
                        <span class="brand-img-text text-theme"><b>K</b></span>
                        
                    </span>
                    <span class="brand-img">
                        <span class="brand-img-text text-theme"><b>P</b></span>
                        
                    </span>
                    
                </a>
            </div>
            <!-- END brand -->

            <!-- BEGIN menu -->
            <div class="menu">
                <div class="menu-item ">
                    <div id="time"></div>
                </div>
                <div class="menu-item dropdown dropdown-mobile-full">
                    <a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
                        <div class="menu-img online">
                            <img src="{{url('sites/img/user/profile.jpg')}}" alt="Profile" height="60" />
                        </div>
                        <div class="menu-text d-sm-block d-none">
                            
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end me-lg-3 fs-11px mt-1">

                        <a class="dropdown-item d-flex align-items-center" href="settings.html">SETTINGS <i
                                class="bi bi-gear ms-auto text-theme fs-16px my-n1"></i></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item d-flex align-items-center"
                            href="controller/logout_controller.php">LOGOUT <i
                                class="bi bi-toggle-off ms-auto text-theme fs-16px my-n1"></i></a>
                    </div>
                </div>
            </div>
            <!-- END menu -->

            <!-- BEGIN menu-search -->
            <form class="menu-search" method="POST" name="header_search_form">
                <div class="menu-search-container">
                    <div class="menu-search-icon"><i class="bi bi-search"></i></div>
                    <div class="menu-search-input">
                        <input type="text" class="form-control form-control-lg" placeholder="Search menu..." />
                    </div>
                    <div class="menu-search-icon">
                        <a href="#" data-toggle-class="app-header-menu-search-toggled" data-toggle-target=".app"><i
                                class="bi bi-x-lg"></i></a>
                    </div>
                </div>
            </form>
            <!-- END menu-search -->
        </div>
@yield('content')
<a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
<!-- END btn-scroll-top -->
</div>
<!-- END #app -->

<!-- ================== BEGIN core-js ================== -->
<script src="{{url('sites/js/vendor.min.js')}}"></script>
<script src="{{url('sites/js/app.min.js')}}"></script>
<!-- ================== END core-js ================== -->

<!-- ================== BEGIN page-js ================== -->
<script src="{{url('sites/plugins/@highlightjs/cdn-assets/highlight.min.js')}}"></script>

<script src="{{url('sites/js/demo/highlightjs.demo.js')}}"></script>
<!-- ================== END page-js ================== -->


<script>
(function(i, s, o, g, r, a, m) {
   i['GoogleAnalyticsObject'] = r;
   i[r] = i[r] || function() {
       (i[r].q = i[r].q || []).push(arguments)
   }, i[r].l = 1 * new Date();
   a = s.createElement(o),
       m = s.getElementsByTagName(o)[0];
   a.async = 1;
   a.src = g;
   m.parentNode.insertBefore(a, m)
})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

ga('create', 'UA-53034621-1', 'auto');
ga('send', 'pageview');
</script>
@yield('script')
</body>

</html>