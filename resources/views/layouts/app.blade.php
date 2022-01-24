<!DOCTYPE html>
<html lang="{{setting('language','en')}}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>{{setting('app_name')}} | {{setting('app_short_description')}}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="icon" type="image/png" href="{{$app_logo}}"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/font-awesome/css/font-awesome.min.css')}}">
   

@stack('css_lib')
<!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-sweetalert/sweetalert.css')}}">
 
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('css/'.setting("theme_color","primary").'.css')}}">

    @yield('css_custom')
    @if (  __('lang.language') == "ar")
        <link rel="stylesheet" href="css/styleRtl.css">

    @endif
    @if (  __('lang.language') != "ar")
        <style>
        .navbar-expand .navbar-nav:first-child
        {margin-right: auto !important}
        </style>
    @endif

    @if (  __('lang.language') == "ar")
    
    <style>
         @font-face {
      font-family: 'arabic';
      src: url('/JF-Flat-Regular.ttf') format('truetype');
    }

     body,h1,h2,h3,h4,h5,h6,span,a,p,li,a {
      font-family: 'arabic', sans-serif !important;
    }
    .main-sidebar, .main-sidebar{
            right:0 !important;
            left:unset !important;
        }

.content-wrapper, .main-footer
{
    direction: rtl !important;
    margin-left:0 !important;
    margin-right:270px !important;
}


.main-header{
    margin-left:0 !important;
    margin-right:270px !important;
    flex-direction: row-reverse !important;
}
.navbar-expand .navbar-nav{
    margin-left:0 !important;
}
.navbar-expand .navbar-nav:first-child
{margin-left: auto !important}

.navbar-expand .navbar-nav .dropdown-menu{
    left:0 !important;
}

.sidebar-collapse .content-wrapper, .sidebar-collapse
.main-footer, .sidebar-collapse
.main-header {
    margin-right: 0 !important;
    margin-left: 0 !important;
}
.sidebar-mini.sidebar-collapse .content-wrapper, .sidebar-mini.sidebar-collapse .main-footer, .sidebar-mini.sidebar-collapse .main-header{
    margin-left: 0 !important;
    margin-right: 4.6rem !important;
    
    
}
.content-header{
    direction: ltr !important;;
}
.text-right {
    text-align: left !important;
}
.dropzone .dz-message span:before{
  left: -155px;
}
.slimScrollBar{
    background:black !important;
    cursor:grap;
}
.breadcrumb-item + .breadcrumb-item::before {
    display: inline-block !important;
    padding-left: 0.5rem !important;
    color: #6c757d !important;
   
    content: "/" !important;
}

.content-header .breadcrumb{
    direction: rtl !important;
}
.content-header {
    direction: rtl !important;
}

.content-header {
    text-align: start;
}

</style>
    @endif
</head>

<body style="height: 100%; background-color: #f9f9f9;" class="hold-transition sidebar-mini {{setting('theme_color')}}">
@if(auth()->check())
    <div class="wrapper">
        <!-- Main Header -->
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand {{setting('fixed_header','')}} {{setting('nav_color','navbar-light bg-white')}} border-bottom">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{url('dashboard')}}" class="nav-link">{{trans('lang.dashboard')}}</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ">
                @if(env('APP_CONSTRUCTION',false))
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="#"><i class="fa fa-info-circle"></i>
                            {{env('APP_CONSTRUCTION','') }}</a>
                    </li>
                @endif

                @can('notifications.index')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('notifications*') ? 'active' : '' }}" href="{!! route('notifications.index') !!}"><i class="fa fa-bell"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('notifications*') ? 'active' : '' }}" href="{!! url('chat-page') !!}"><i class="fa fa-comment"></i></a>
                    </li>
                @endcan
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <img src="{{auth()->user()->getFirstMediaUrl('avatar','icon')}}" class="brand-image mx-2 img-circle elevation-2" alt="User Image">
                        <i class="fa fa fa-angle-down"></i> {!! auth()->user()->name !!}

                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{route('users.profile')}}" class="dropdown-item"> <i class="fa fa-user mr-2"></i> {{trans('lang.user_profile')}} </a>
                        <div class="dropdown-divider"></div>
                        <a href="{!! url('/logout') !!}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-envelope mr-2"></i> {{__('auth.logout')}}
                        </a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
                <li class="nav-item dropdown">
                
                <a class="nav-link" data-toggle="dropdown" href="#" id="data_language_name">
                       
                       {{__('auth.data_language')}}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        
                        <a class="dropdown-item" onclick="event.preventDefault(); put_language('en'); document.getElementById('language-form').submit();">
                            <i class="fa mr-2"></i> {{__('auth.english')}}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" onclick="event.preventDefault(); put_language('ar'); document.getElementById('language-form').submit();">
                            <i class="fa mr-2"></i> {{__('auth.arabic')}}
                        </a>
                        <form id="language-form" action="{{ url('changeLanguage') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                            <input type="hidden" name="language_id" id="language_input">
                        </form>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav ">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" id="language_name">
                       {{__('auth.language')}}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ url('setlang/ar') }}" class="dropdown-item"> {{__('auth.arabic')}} </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ url('setlang/en') }}" class="dropdown-item" >
                        {{__('auth.english')}}
                        </a>
                     
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Left side column. contains the logo and sidebar -->
    @include('layouts.sidebar')
    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Main Footer -->
        <footer class="main-footer {{setting('fixed_footer','')}}">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> {{setting('app_version')}}
            </div>
            <strong>Copyright © {{date('Y')}} <a href="{{url('/')}}">{{setting('app_name')}}</a>.</strong> All rights reserved.
        </footer>

    </div>
@else
    <nav class="nmain-header navbar navbar-expand {{setting('nav_color','navbar-light bg-white')}} border-bottom">
        <div class="container">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{!! url('/') !!}">{{setting('app_name')}}</a>
                </li>
                @include('layouts.menu',['icons'=>false])
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        {!! Auth::user()->name !!}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{route('users.profile')}}" class="dropdown-item"> <i class="fa fa-user mr-2"></i> Profile </a>
                        <div class="dropdown-divider"></div>
                        <a href="{!! url('/logout') !!}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-envelope mr-2"></i> {{__('auth.logout')}}
                        </a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>

          


        </div>
    </nav>

    <div id="page-content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
            <!-- Main Footer -->
            <footer class="{{setting('fixed_footer','')}}">
                <div class="float-right d-none d-sm-block">
                    <b>Version</b> {{setting('app_version')}}
                </div>
                <strong>Copyright © {{date('Y')}} <a href="{{url('/')}}">{{setting('app_name')}}</a>.</strong> All rights reserved.
            </footer>
        </div>
    </div>

    @endrole


    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{asset('https://www.gstatic.com/firebasejs/7.2.0/firebase-app.js')}}"></script>

    <script src="{{asset('https://www.gstatic.com/firebasejs/7.2.0/firebase-messaging.js')}}"></script>

    <script type="text/javascript">
        @include('vendor.notifications.init_firebase')
    </script>
	<audio id="notification" src='{{url("1.mp3")}}' muted > </audio>

    <script>

</script>

    <script type="text/javascript">

        $(document).ready(function() {
            get_language();
        });

        function put_language(id){
            $('#language_input').val(id);
        }

        function get_language(id){
            $.ajax({
                type: "get",
                url: "{{url('get-languages')}}",
                success: function (data) {
                    $('#data_language_name').append(data.data_language);
                    $('#language_name').append(data.language);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        const messaging = firebase.messaging();
        navigator.serviceWorker.register("{{url('firebase/sw-js')}}")
            .then((registration) => {
                messaging.useServiceWorker(registration);
                messaging.requestPermission()
                    .then(function() {
                        console.log('Notification permission granted.');
                        getRegToken();

                    })
                    .catch(function(err) {
                        console.log('Unable to get permission to notify.', err);
                    });
                messaging.onMessage(function(payload) {

                    console.log("Message received. ", payload);
                    notificationTitle = payload.data.title;
                    notificationOptions = {
                        body: payload.data.body,
                        icon: payload.data.icon,
                        image:  payload.data.image
                    };
                    var notification = new Notification(notificationTitle,notificationOptions);
                   // 
                    document.getElementById('notification').muted = false;
 				    document.getElementById('notification').play();
                    if($(`#sendmessages`).length > 0){
                        getmessageschats();
                    }
                });
            });

        function getRegToken(argument) {
            messaging.getToken().then(function(currentToken) {
                if (currentToken) {
                    saveToken(currentToken);
                    console.log(currentToken);
                } else {
                    console.log('No Instance ID token available. Request permission to generate one.');
                }
            })
                .catch(function(err) {
                    console.log('An error occurred while retrieving token. ', err);
                });
        }


        function saveToken(currentToken) {
            $.ajax({
                type: "POST",
                data: {'device_token': currentToken, 'api_token': '{!! auth()->user()->api_token !!}'},
                url: '{!! url('api/users',['id'=>auth()->id()]) !!}',
                success: function (data) {

                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    </script>


    <script src="{{asset('plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-sweetalert/sweetalert.min.js')}}"></script>
   
    @stack('scripts_lib')
    <script src="{{asset('dist/js/adminlte.js')}}"></script>

    <script src="{{asset('dist/js/demo.js')}}"></script>

    <script src="{{asset('js/scripts.js')}}"></script>
    @stack('scripts')

</body>
</html>