<nav class="pcoded-navbar">
    <div class="pcoded-inner-navbar main-menu">
        <div class="pcoded-navigatio-lavel"></div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="{{ request()->is('/') ? 'active' : '' }}">
                <a href="{{url('')}}">
                    <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                    <span class="pcoded-mtext">Dashboard</span>
                </a>
            </li>
            <li class="pcoded-hasmenu {{ request()->is('master/*') ? 'active' : '' }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="feather icon-grid"></i></span>
                    <span class="pcoded-mtext">Utama</span>
                </a>
                <ul class="pcoded-submenu">
                    <li class="{{ request()->is('master/class*') ? 'active' : ''}}">
                        <a href="{{url('master/class')}}">
                            <span class="pcoded-mtext">Kelas</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('master/student*') ? 'active' : '' }}">
                        <a href="{{url('master/student')}}">
                            <span class="pcoded-mtext">Peserta</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('master/absen-type*') ? 'active' : '' }}">
                        <a href="{{url('master/absen-type')}}">
                            <span class="pcoded-mtext">Jenis Absen</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('master/quiz*') ? 'active' : '' }}">
                        <a href="{{url('master/quiz')}}">
                            <span class="pcoded-micon"><i class="feather icon-edit-1"></i></span>
                            <span class="pcoded-mtext">Tingkatan</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="{{ request()->is('/quiz-student') ? 'active' : '' }}">
                <a href="{{url('quiz-student')}}">
                    <span class="pcoded-micon"><i class="feather icon-edit-1"></i></span>
                    <span class="pcoded-mtext">Isi Penilaian</span>
                </a>
            </li>
            <li class="{{ request()->is('/report') ? 'active' : '' }}">
                <a href="{{url('report')}}">
                    <span class="pcoded-micon"><i class="feather icon-file"></i></span>
                    <span class="pcoded-mtext">Cetak Rapot</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
