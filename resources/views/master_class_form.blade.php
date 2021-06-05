@extends('layouts.appLayout')

@section('style')
    <style>
        .custom-form {
            margin-top: 50px;
        }
    </style>
@endsection

@section('content')
    <div class="page-body button-page">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Masukkan Data Kelas</h5>

                        <form action="{{ $action }}" method="post" >
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group row custom-form">
                                        <div class="col-sm-12 col-md-12">
                                            <label class="block"> Nama Kelas </label>
                                            <input class="form-control" type="text" name="name_class_room" value="{{old('name_class_room')}}" placeholder="contoh: MUSTAWAL AWWAL">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-12">
                                            <label class="block"> Penjelasan Kelas </label>
                                            <textarea
                                                class="form-control" name="description"
                                                id="description" cols="30" rows="10"
                                                placeholder="contoh : Absensi Kelas IQRO Senin, 12.30 - 14.00 wib/ 13.30 - 15.00 wita"
                                                >{{old('description')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-12">
                                            <button type="submit" class="btn btn-md btn-success" id="btnSave">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        var state = {
            changePassword: false,
        }

        var btnSave = $('#btnSave');

        $(document).ready(function() {


        });
    </script>
@endsection
