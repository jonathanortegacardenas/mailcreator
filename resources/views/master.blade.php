<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>{{$title}} - Creador de Mails EasyMail UDLA</title>
        <meta name="description" content="Creador de Mails UDLA">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <!--base css styles-->
        <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/font-awesome/css/font-awesome.min.css')}}">

        <!--page specific css styles-->
        @section('styles')
        @show
        <!--flaty css styles-->
        <link rel="stylesheet" href="{{asset('css/flaty.css')}}">
        <link rel="stylesheet" href="{{asset('css/flaty-responsive.css')}}">
        <link rel="shortcut icon" href="{{asset('img/favicon.png')}}">
    </head>
    <body>

        <!-- BEGIN Navbar -->
        <div id="navbar" class="navbar">
            <button type="button" class="navbar-toggle navbar-btn collapsed" data-toggle="collapse" data-target="#sidebar">
                <span class="fa fa-bars"></span>
            </button>
            <a class="navbar-brand" href="#">
                <small>
                    <i class="fa fa-desktop"></i>
                    <b>Easy</b>Mail v0.7
                </small>
            </a>

            <!-- BEGIN Navbar Buttons -->
            <ul class="nav flaty-nav pull-right">
                <!-- BEGIN Button User -->
                <li class="user-profile">
                    <a data-toggle="dropdown" href="#" class="user-menu dropdown-toggle">
                        <span id="user_info">
                        </span>
                        <i class="fa fa-caret-down"></i>
                    </a>

                    <!-- BEGIN User Dropdown -->
                    <ul class="dropdown-menu dropdown-navbar" id="user_menu">
                        <li>
                            <a href="#">
                                <i class="fa fa-user"></i>
                                Editar Perfil
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="{{url('logout')}}">
                                <i class="fa fa-off"></i>
                                Cerrar Sesi&oacute;n
                            </a>
                        </li>
                    </ul>
                    <!-- BEGIN User Dropdown -->
                </li>
                <!-- END Button User -->
            </ul>
            <!-- END Navbar Buttons -->
        </div>
        <!-- END Navbar -->

        <!-- BEGIN Container -->
        <div class="container" id="main-container">
            <!-- BEGIN Sidebar -->
            <div id="sidebar" class="navbar-collapse collapse">
                <!-- BEGIN Navlist -->
                <ul class="nav nav-list">
                    <li>
                        <a href="{{url('/')}}">
                            <i class="fa fa-dashboard"></i>
                            <span>Inicio</span>
                        </a>
                    </li>
                    <!-- {{$active_user = ""}} -->
                    @if(\Auth::user()->type==0)
                    @if(isset($class)&&$class=="users")
                        <!-- {{$active_user = "active"}} -->
                    @endif
                    <li class="{{$active_user}}">
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-users"></i>
                            <span>Usuarios</span>
                            <b class="arrow fa fa-angle-right"></b>
                        </a>

                        <!-- BEGIN Submenu -->
                        <ul class="submenu">
                            <li><a href="{{url('users/list')}}">Listado de Usuarios</a></li>
                            <li><a href="{{url('users/add')}}">Agregar Usuario</a></li>
                        </ul>
                        <!-- END Submenu -->
                    </li>
                    @endif
                    <!-- {{$active_campaigns = ""}} -->
                    @if(isset($class)&&$class=="campaigns")
                        <!-- {{$active_campaigns = "active"}} -->
                    @endif
                    <li class="{{$active_campaigns}}">
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-flag-checkered"></i>
                            <span>Campa&ntilde;as</span>
                            <b class="arrow fa fa-angle-right"></b>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{url('campaigns/list')}}">Listado de Campa&ntilde;as</a></li>
                            <li><a href="{{url('campaigns/add')}}">Agregar Campa&ntilde;a</a></li>
                        </ul>
                    </li>
                </ul>
                <!-- END Navlist -->

                <!-- BEGIN Sidebar Collapse Button -->
                <div id="sidebar-collapse" class="visible-lg">
                    <i class="fa fa-angle-double-left"></i>
                </div>
                <!-- END Sidebar Collapse Button -->
            </div>
            <!-- END Sidebar -->

            <!-- BEGIN Content -->
            <div id="main-content">
                <!-- BEGIN Page Title -->
                <div class="page-title">
                    <div>
                        <h1><i class="fa fa-file-o"></i> {{$title}}</h1>
                        <h4>{{$message}}</h4>
                    </div>
                </div>
                <!-- END Page Title -->

                <!-- BEGIN Breadcrumb -->
                <div id="breadcrumbs">
                    <ul class="breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="{{url('/')}}">Inicio</a>
                            <span class="divider"><i class="fa fa-angle-right"></i></span>
                        </li>
                        <li class="active">{{$title}}</li>
                    </ul>
                </div>
                <!-- END Breadcrumb -->

                <!-- BEGIN Main Content -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-title">
                                <h3><i class="fa fa-file"></i> {{$title}}</h3>
                                <div class="box-tool">
                                    <a data-action="collapse" href="#"><i class="fa fa-chevron-up"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
                               @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Main Content -->
                
                <footer>
                    <p>{{date('Y')}}, &copy; Universidad de Las Am&eacute;ricas</p>
                </footer>

                <a id="btn-scrollup" class="btn btn-circle btn-lg" href="#"><i class="fa fa-chevron-up"></i></a>
            </div>
            <!-- END Content -->
        </div>
        <!-- END Container -->


        <!--basic scripts-->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="{{asset('assets/jquery/jquery-2.1.1.min.js')}}"><\/script>')</script>
        <script src="{{asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('assets/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
        <script src="{{asset('assets/jquery-cookie/jquery.cookie.js')}}"></script>

        <!--page specific plugin scripts-->
        @section('scripts')
        @show

        <!--flaty scripts-->
        <script src="{{asset('js/flaty.js')}}"></script>
        <script src="{{asset('js/flaty-demo-codes.js')}}"></script>
        

    </body>
</html>
