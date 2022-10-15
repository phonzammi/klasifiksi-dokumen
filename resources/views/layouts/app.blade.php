<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/dashboard.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}"> --}}
    @livewireStyles

    <!-- Scripts -->
    {{-- <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script> --}}
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ mix('js/dashboard.js') }}"></script>
</head>

<body class="hold-transition sidebar-mini">
    {{-- <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed font-sans antialiased"> --}}
    <div class="wrapper">

        <!-- Navbar -->
        @livewire('navigation-menu')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/" class="brand-link">
                <x-jet-application-mark width="36" class="brand-image img-circle elevation-1" style="opacity: .8" />
                <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="image">
                            <img src="{{ Auth::user()->profile_photo_url }}" class="img-circle elevation-1"
                                alt="{{ Auth::user()->name }}">
                        </div>
                    @endif
                    <div class="info">
                        <a href="{{ route('profile.show') }}" class="d-block">{{ Auth::user()->name }}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview"
                        role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                                 with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>


                        <li class="nav-item {{ request()->routeIs('admin.documents.*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>
                                    Dokumen
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.documents.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.documents.index') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-file-upload"></i>
                                        <p>List Dokumen</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.documents.jenis') }}"
                                        class="nav-link {{ request()->routeIs('admin.documents.jenis') ? 'active' : '' }}">
                                        <i class="fas fa-list-ul nav-icon"></i>
                                        <p>List Jenis Dokumen</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.users') }}"
                                class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>List Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.roles') }}"
                                class="nav-link {{ request()->routeIs('admin.roles') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-tag"></i>
                                <p>List Hak Akses (Jabatan)</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        {{-- <div class="col"> --}}
                        {{ $header }}
                        {{-- </div> --}}
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                {{-- <div class="container-fluid"> --}}
                {{-- <div class="row"> --}}
                {{-- <div class="col"> --}}
                {{ $slot }}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b><a href="https://jetstream.laravel.com">Jetstream</a></b>
            </div>
            <strong>Powered by</strong> <a href="https://adminlte.io">AdminLTE</a>
        </footer>
    </div>
    <!-- jQuery -->
    {{-- <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script> --}}
    <!-- Select2 -->
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    @stack('modals')
    @livewireScripts
    @stack('scripts')
</body>

</html>
