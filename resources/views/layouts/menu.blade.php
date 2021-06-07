<nav class="pcoded-navbar">
    <div class="pcoded-inner-navbar main-menu">
        <div class="pcoded-navigatio-lavel">Navigation</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="{{ active_menu('/') }}">
                <a href="{{url('')}}">
                    <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                    <span class="pcoded-mtext">Dashboard</span>
                </a>
            </li>
            <li class="pcoded-hasmenu {{ active_menu_list('master/*') }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="feather icon-grid"></i></span>
                    <span class="pcoded-mtext">Utama</span>
                </a>
                <ul class="pcoded-submenu">
                    <li class="{{ active_menu('master/class*') }}">
                        <a href="{{url('master/class')}}">
                            <span class="pcoded-mtext">Kelas</span>
                        </a>
                    </li>
                    <li class="{{ active_menu('master/student*') }}">
                        <a href="{{url('master/student')}}">
                            <span class="pcoded-mtext">Peserta</span>
                        </a>
                    </li>
                    <li class="{{ active_menu('master/absen-type*') }}">
                        <a href="{{url('master/absen-type')}}">
                            <span class="pcoded-mtext">Jenis Absen</span>
                        </a>
                    </li>
                    <li class="{{ active_menu('master/quiz*') }}">
                        <a href="{{url('master/quiz')}}">
                            <span class="pcoded-micon"><i class="feather icon-edit-1"></i></span>
                            <span class="pcoded-mtext">Kuis</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="{{ active_menu('quiz-student') }}">
                <a href="{{url('quiz-student')}}">
                    <span class="pcoded-micon"><i class="feather icon-edit-1"></i></span>
                    <span class="pcoded-mtext">Isi Kuis</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
