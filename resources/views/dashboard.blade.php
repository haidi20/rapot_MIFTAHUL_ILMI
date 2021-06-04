@extends('layouts.appLayout')

@section('style')
    <style>
        .page-body {
            height: 100vh;
        }
    </style>
@endsection

@section('content')
    <div class="page-body button-page">
        <div class="row">
            <div class="col-xl-3 col-md-3">
                <div class="card bg-c-yellow text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <p class="m-b-5">Peserta</p>
                                <h4 class="m-b-0"> 5 </h4>
                            </div>
                            <div class="col col-auto text-right">
                                <i class="feather icon-user f-50 text-c-yellow"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-3">
                <div class="card bg-c-green text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <p class="m-b-5">Kelas</p>
                                <h4 class="m-b-0"> 9 </h4>
                            </div>
                            <div class="col col-auto text-right">
                                {{-- <i class="feather icon-class f-50 text-c-green"></i> --}}
                                <i class="fa fa-book f-50 text-c-green" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
