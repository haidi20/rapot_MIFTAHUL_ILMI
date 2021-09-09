@extends('layouts.appLayout')

@section('style')
    <!-- select2 css -->
    <link rel="stylesheet" href="{{asset('adminty/files/bower_components/select2/css/select2.min.css')}}"/>
    <!-- Date-time picker css -->
    <link rel="stylesheet" type="text/css" href="{{asset('adminty/files/assets/pages/advance-elements/css/bootstrap-datetimepicker.css')}}">
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('adminty/files/assets/icon/font-awesome/css/font-awesome.min.css')}}"> --}}
    <style>
        .btn-filter {
            margin-top: 30px;
        }

        .btn-save {
            float: right;
            margin-top: 35px;
        }
        .btn-delete-student {
            float: right;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="page-body">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="row">

                    <div class="col-sm-12 col-md-12">
                        {!! session()->get('message') !!}
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-12 col-md-3">
                                        <label class="block"> Bulan </label>
                                        <input type="month" name="filter_datetime" id="filter_datetime" class="form-control">
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <label class="block"> Kelas </label>
                                        <select name="class_room_id" id="filter_class" class="form-control form-control-inverse">
                                            <option value="">Pilih Kelas</option>
                                            @foreach ($classRoom as $index => $item )
                                                <option
                                                    value="{{$item->id}}">
                                                    {{$item->name_class_room}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <label class="block"> Tingkatan </label>
                                        <select name="filter_quiz_id" id="filter_quiz" class="form-control form-control-inverse">
                                            <option value="">Pilih Tingkatan</option>
                                            @foreach ($quiz as $index => $item )
                                                <option
                                                    value="{{$item->id}}">
                                                    {{$item->description}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <button type="button" class="btn btn-sm btn-success btn-filter" onclick="sendFilter()">Pilih</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <div class="card">
                            <div class="card-body">

                                <form action="{{ $action }}" method="post" id="form" >
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="form-group row">
                                                <div class="col-sm-12 col-md-12">
                                                    <a href="#" type="button" class="btn btn-sm btn-info" onclick="addFormStudent()">Tambah Peserta</a>
                                                </div>
                                            </div>
                                            <input type="hidden" name="class_room_id" id="class_room_id">
                                            <input type="hidden" name="quiz_id" id="quiz_id">
                                            <input type="hidden" name="datetime" id="datetime">
                                            {{-- <div class="form-group row">
                                                <div class="col-sm-12 col-md-12">
                                                    <label class="block"> Kelas </label>
                                                    <select name="class_room_id" id="class_room_id" class="form-control select2 form-control-inverse">
                                                        <option value="">Pilih Kelas</option>
                                                        @foreach ($classRoom as $index => $item )
                                                            <option
                                                                value="{{$item->id}}">
                                                                {{$item->name_class_room}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-12 col-md-12">
                                                    <label class="block"> Tingkatan </label>
                                                    <select name="quiz_id" id="quiz_id" class="form-control select2 form-control-inverse">
                                                        <option value="">Pilih Tingkatan</option>
                                                        @foreach ($quiz as $index => $item )
                                                            <option
                                                                value="{{$item->id}}">
                                                                {{$item->description}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div> --}}
                                            <div class="form-group row multi-student">
                                                <div class="col-sm-12 col-md-12">
                                                    <label class="block"> Peserta </label>
                                                    <select name="students[]" id="first_student" class=" form-control select2 form-student">
                                                        <option value="">Pilih Peserta</option>
                                                        @foreach ($student as $index => $item )
                                                            <option
                                                                value="{{$item->id}}">
                                                                {{$item->name_student}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-12 col-md-12">
                                                    <button type="submit" class="btn btn-sm btn-success btn-save disabled" disabled id="btnSave">Kirim</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <div class="card">
                            <div class="card-body">
                                <h5> Daftar Peserta Aktif </h5>

                                <table id="student_active"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-3">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ $actionDateAbsen }}" method="post" id="form">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <div class="col-sm-12 col-md-12">
                                    <label class="block"> Pertemuan </label>
                                    <input type="text" name="date_absen" class="form-control form-date-absen" placeholder="format: bulan/tanggal | 05/18">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12 col-md-12">
                                    <button type="submit" class="btn btn-sm btn-success btn-save" id="btnSaveDateAbsen">Kirim</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-9">
                <div class="card">
                    <div class="card-body">
                        <h5> Daftar Tanggal Pertemuan </h5>

                        <table id="tbl_date_absen"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <!-- Select 2 js -->
    <script type="text/javascript" src="{{asset('adminty/files/bower_components/select2/js/select2.full.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('adminty/files/assets/pages/advance-elements/moment-with-locales.min.js')}}"></script>
    <script>
        let state = {
            quiz_id: '',
            dataStudent: [],
            class_room_id: '',
            countFormStudent: 1,
            datetime: moment().format('YYYY-MM'),
        }

        let data = [];

        let btnSave = $('#btnSave');
        let frmDateTime = $('#datetime');
        let multiStudent = $('.multi-student');
        let tblStudentActive = $('#student_active');
        let tblDateAbsen = $('#tbl_date_absen');

        let filterQuiz = $('#filter_quiz');
        let filterClass = $('#filter_class');
        let filterDatetime = $('#filter_datetime');

        $(document).ready(function() {
            readData();
            loadDataStudent();
            conditionBtnSend();

            filterDatetime.val(state.datetime);
            frmDateTime.val(state.datetime);

            filterDatetime.change(function(){
                frmDateTime.val(filterDatetime.val());
            });

            readDataDateAbsen();
        });

        function remove(index) {
            data = tblStudentActive.bootstrapTable('getData');

            Swal.fire({
                title: 'Yakin hapus data kelas <b>'+data[index].name_student+'</b> ?',
                showDenyButton: false,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: `Delete`,
                confirmButtonColor: 'red',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    location.href = `{{url('student-active/delete')}}/${data[index].id}`;
                    // Swal.fire('Data terhapus!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Data tidak dihapus', '', 'info');
                }
            });
        }

        function conditionBtnSend() {
            filterQuiz.on('change', function(){
                $('#quiz_id').val(filterQuiz.val());

                if(filterClass.val() && filterQuiz.val() != ''){
                    btnSave.removeAttr('disabled').removeClass('disabled');
                }else{
                    btnSave.addClass('disabled').prop('disabled', true);
                }
            });

            filterClass.on('change', function(){
                $('#class_room_id').val(filterClass.val());

                if(filterClass.val() && filterQuiz.val() != ''){
                    btnSave.removeAttr('disabled').removeClass('disabled');
                }else{
                    btnSave.addClass('disabled').prop('disabled', true);
                }
            });
        }

        function addFormStudent() {
            let newFormStudent = '';
            let optionNewFormStudent = '';
            let countFormStudent = state.countFormStudent + 1;
            state.countFormStudent = countFormStudent;

            optionNewFormStudent += '<option> Pilih Peserta </option>';
            $.each(state.dataStudent, function(index, item) {
                optionNewFormStudent += '<option value="'+item.id+'"> '+item.name_student+' </option>';
            });

            newFormStudent += '<div class="form-group row multi-student" id="form_student_'+countFormStudent+'">';
                newFormStudent +='<div class="col-sm-12 col-md-12">';
                    newFormStudent +='<label class="block"> Pilih Peserta </label> <i onclick="removeFormStudent('+countFormStudent+')" class="fas fa-close text-danger btn-delete-student"></i>';
                    newFormStudent +='<select name="students[]" class="form-control select2 form-control-inverse">';
                        newFormStudent += optionNewFormStudent;
                    newFormStudent +='</select>';
                    // newFormStudent +='<button type="submit" class="btn btn-sm btn-danger btn-delete-student" onclick="removeFormStudent('+countFormStudent+')">';
                    //     newFormStudent += '<i class="fas fa-close"></i>';
                    // newFormStudent +='</button>';
                newFormStudent +='</div>';
            newFormStudent +='</div>';

            multiStudent.after(newFormStudent);

            // $('.select2').select2();
        }

        function removeFormStudent(index) {
            let countFormStudent = state.countFormStudent - 1;
            state.countFormStudent = countFormStudent;

            $('#form_student_'+index).remove();
        }

        function sendFilter() {
            state.quiz_id = filterQuiz.val();
            state.class_room_id = filterClass.val();
            state.datetime = filterDatetime.val();
            // state.dataAbsen = [];

            tblStudentActive.bootstrapTable('refresh');
            tblDateAbsen.bootstrapTable('refresh');
        }

        function readData() {
            tblStudentActive.bootstrapTable({
                url: "{{url('student-active/ajax-read-student-active')}}",
                method: 'get',
                locale: 'en-US',
                classes: 'table table-bordered table-hover',
                toolbar: '#toolbar',
                //showRefresh: true,
                search: true,
                pagination: true,
                sidePagination: 'server',
                pageSize: 15,
                sortable: true,
                //idField: 'NRP',
                queryParams: function(params) {
                    params.quiz_id = state.quiz_id;
                    params.class_room_id = state.class_room_id;
                    params.datetime = state.datetime;

                    return params;
                },
                columns: [
                    {
                        width: 10,
                        title: 'Action',
                        formatter: function (value, row, index) {
                            let str = '';
                            str += `<a type="button" id="remove_${index}" onclick="remove('${index}')" class="btn btn-danger btn-xsm"><i class="fas fa-trash"></i></a>`;

                            return str;
                        },
                    },
                    // {
                    //     field: 'name_class_room',
                    //     title: 'Nama Kelas',
                    // },
                    {
                        field: 'name_student',
                        title: 'Nama Peserta',
                    },
                    {
                        field: 'nis',
                        title: 'NIS',
                    },
                ]
            });
        }

        function readDataDateAbsen() {
            tblDateAbsen.bootstrapTable({
                url: "{{url('student-active/ajax-read-date-absen')}}",
                method: 'get',
                locale: 'en-US',
                classes: 'table table-bordered table-hover',
                toolbar: '#toolbar',
                //showRefresh: true,
                search: true,
                pagination: true,
                sidePagination: 'server',
                pageSize: 4,
                sortable: true,
                //idField: 'NRP',
                queryParams: function(params) {
                    params.quiz_id = state.quiz_id;
                    params.class_room_id = state.class_room_id;
                    params.datetime = state.datetime;

                    return params;
                },
                columns: [
                    {
                        width: 10,
                        title: 'Action',
                        formatter: function (value, row, index) {
                            let str = '';
                            str += `<a type="button" id="remove_${index}" onclick="removeDateAbsen('${index}')" class="btn btn-danger btn-xsm"><i class="fas fa-trash"></i></a>`;

                            return str;
                        },
                    },
                    // {
                    //     field: 'name_class_room',
                    //     title: 'Nama Kelas',
                    // },
                    {
                        field: 'date',
                        title: 'Tanggal Pertemuan',
                    },
                ]
            });
        }

        function remove(index) {
            data = tblStudentActive.bootstrapTable('getData');

            Swal.fire({
                title: 'Yakin hapus peserta <b>'+data[index].name_student+'</b> di kelas <b>'+data[index].name_class_room+'</b> dan tingkat <b>'+data[index].description+'</b> ?',
                showDenyButton: false,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: `Delete`,
                confirmButtonColor: 'red',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    location.href = `{{url('student-active/delete-student-active')}}/${data[index].id}`;
                    // Swal.fire('Data terhapus!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Data tidak dihapus', '', 'info');
                }
            });
        }

        function removeDateAbsen(index) {
            data = tblDateAbsen.bootstrapTable('getData');

            Swal.fire({
                title: 'Yakin hapus tanggal pertemuan <b>'+data[index].date+'</b> ?',
                showDenyButton: false,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: `Delete`,
                confirmButtonColor: 'red',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    location.href = `{{url('student-active/delete-date-absen')}}/${data[index].id}`;
                    // Swal.fire('Data terhapus!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Data tidak dihapus', '', 'info');
                }
            });
        }

        function loadDataStudent() {
            $.ajax({
                url: "{{url('master/student/ajaxReadTypeahead')}}",
                dataType: "JSON",
                contentType: "application/json",
                type: "GET",
                success: function (result) {
                    if (result.data) {
                        let optionNewFormStudent = '';
                        state.dataStudent = result.data;
                    }
                },
                error: function (error) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops... Error...',
                        html: "Maaf, gagal mendapatkan data peserta",
                    });

                    console.log(error);
                }
            });
        }
    </script>
@endsection
