@if (  __('lang.language') == "ar")
<style>

  .nav-sidebar > .nav-item{
    direction: rtl;
    text-align: right;
  }

  .nav-sidebar .nav-link > p > .right {
    position: absolute;
    left: 1rem;
    right:unset;
    top: 12px;
}
.sidebar-mini .nav-sidebar, .sidebar-mini .nav-sidebar > .nav-header, .sidebar-mini .nav-sidebar .nav-link{
    text-align: right;
    
}

body:not(.sidebar-collapse) .main-sidebar .nav-treeview > .nav-item > .nav-link {
    color: #676767;
    padding-right: 2rem;
    padding-left: 0;
    font-size: 90% !important;
}

body:not(.sidebar-collapse) .main-sidebar .nav-treeview .nav-treeview .nav-item > .nav-link {
    padding-right: 3rem;
    padding-left: 0;
    
}
.sidebar-mini.sidebar-collapse .main-sidebar:hover .user-panel {
        text-align: right;
    }
    .navbar-custom-menu > .navbar-nav {
        float: left;
    }
    .nav {
    flex-wrap: unset;
    }
    /* .sidebar-mini.sidebar-collapse .main-sidebar:hover .user-panel .image {
        float: right;
    } */
/* .sidebar-mini .nav-sidebar, .sidebar-mini .nav-sidebar > .nav-header, .sidebar-mini .nav-sidebar .nav-link{
    text-align: left;
    
} */
/* .sidebar-collapse .main-sidebar, .sidebar-collapse .main-sidebar:before {
    margin-left: 0px;
    margin-right: -270px;
} */
/* .sidebar-collapse .nav-icon .fa .fa-dashboard,
.sidebar-collapse img{
   display:none;
}  */
</style>
@endif
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-{{setting('theme_contrast')}}-{{setting('theme_color')}} elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('dashboard')}}" class="brand-link {{setting('logo_bg_color','bg-white')}}">
        <img src="{{$app_logo}}" alt="{{setting('app_name')}}" class="brand-image">
        <span class="brand-text font-weight-light">{{setting('app_name')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu',['icons'=>true])
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
