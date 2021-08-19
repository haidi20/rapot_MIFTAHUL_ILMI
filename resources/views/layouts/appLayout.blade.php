@extends('layouts.mainLayout')

@section('style')
    <style>
        #title_app > h5 {
            text-align: left !important;
            margin-top: 10px !important;
        }
    </style>
@endsection

@section('body')
    @include('layouts.loading')

    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">

            <nav class="navbar header-navbar pcoded-header">
                <div class="navbar-wrapper">

                    <div class="navbar-logo">
                        <a class="mobile-menu" id="mobile-collapse" href="#!">
                            <i class="feather icon-menu"></i>
                        </a>
                        <a id="title_app" href="#">
                            {{-- <img class="img-fluid" src="../files/assets/images/logo.png" alt="Theme-Logo" /> --}}
                            <h5 style="text-align: left; margin-top: 18px;">Raport</h5>
                            <p>MIFTAHUL ILMI SAMARINDA</p>
                        </a>
                        <a class="mobile-options">
                            <i class="feather icon-more-horizontal"></i>
                        </a>
                    </div>

                    <div class="navbar-container container-fluid">
                        <ul class="nav-left">
                            <li class="header-search">
                                <div class="main-search morphsearch-search">
                                    <div class="input-group">
                                        <span class="input-group-addon search-close"><i class="feather icon-x"></i></span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="#!" onclick="javascript:toggleFullScreen()">
                                    <i class="feather icon-maximize full-screen"></i>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav-right">


                            <li class="user-profile header-notification">
                                <div class="dropdown-primary dropdown">
                                    <div class="dropdown-toggle" data-toggle="dropdown">
                                        {{-- <img src="../files/assets/images/avatar-4.jpg" class="img-radius" alt="User-Profile-Image"> --}}
                                        <span>{{session()->get('Username')}}</span>
                                        <i class="feather icon-chevron-down"></i>
                                    </div>
                                    <ul class="show-notification profile-notification dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                        {{-- <li>
                                            <a href="#!">
                                                <i class="feather icon-settings"></i> Settings
                                            </a>
                                        </li> --}}
                                        {{-- <li>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                                         <i class="feather icon-log-out"></i>
                                                Logout
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </li> --}}
                                    </ul>

                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div className="pcoded-main-container">
                <div className="pcoded-wrapper">

                    @include('layouts.menu')

                    <div className="pcoded-content">
                        <div className="pcoded-inner-content " style="margin-top: 50px">
                            <div className="main-body">
                                <div className="page-wrapper">
                                    <div class="pcoded-content">
                                        <div class="pcoded-inner-content">
                                            <!-- Main-body start -->
                                            <div class="main-body">
                                                <div class="page-wrapper">
                                                    @yield('content')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div id="app"></div> --}}
@endsection
