@extends('layouts.appLayout')

@section('style')
    <!-- select2 css -->
    <link rel="stylesheet" href="{{asset('adminty/files/bower_components/select2/css/select2.min.css')}}"/>
    <!-- Date-time picker css -->
    <link rel="stylesheet" type="text/css" href="{{asset('adminty/files/assets/pages/advance-elements/css/bootstrap-datetimepicker.css')}}">
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('adminty/files/assets/icon/font-awesome/css/font-awesome.min.css')}}"> --}}
    <style>
        .bootstrap-table .fixed-table-container .table thead th {
            vertical-align: middle;
        }
        .custom-form {
            margin-top: 50px;
        }
        .form-select-student {
            width: 85%;
            display: inline-block;
        }
        .btn-delete-student {
            margin-left: 5px;
            display: inline-block;
        }
        .btn-filter {
            margin-top: 30px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 0px;
            padding-left: 10px;
            color: white;
            /* height: calc(2.25rem + 2px); */
        }
        .form-date-absen {
            width: 100px;
        }


    </style>
@endsection

@section('content')
    <div class="page-body">
        <div class="row">
            <div class="col-sm-12 col-md-3">
                <div class="card">
                    <div class="card-body">
                        {{-- <h5>Masukkan Data Murid</h5> --}}

                        <form action="{{ $action }}" method="post" id="form" >
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-12">
                                            <a href="#" type="button" class="btn btn-sm btn-info" onclick="addFormStudent()">Tambah Peserta</a>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-12">
                                            <label class="block"> Kelas </label>
                                            <select name="class_room_id" id="class_room_id" class="form-control form-control-inverse">
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
                                            <label class="block"> Kuis </label>
                                            <select name="quiz_id" id="quiz_id" class="form-control form-control-inverse">
                                                <option value="">Pilih Kuis</option>
                                                @foreach ($quiz as $index => $item )
                                                    <option
                                                        value="{{$item->id}}">
                                                        {{$item->name_quiz}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
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
                                            <button type="submit" class="btn btn-md btn-success " id="btnSave">Kirim</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-9">
                <div class="row">
                    <div class="col-md-12">
                        {!! session()->get('message') !!}
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
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
                                    <div class="col-sm-12 col-md-4">
                                        <label class="block"> Kuis </label>
                                        <select name="filter_quiz_id" id="filter_quiz" class="form-control form-control-inverse">
                                            <option value="">Pilih Kuis</option>
                                            @foreach ($quiz as $index => $item )
                                                <option
                                                    value="{{$item->id}}">
                                                    {{$item->name_quiz}}
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
                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-body">
                                <h5> Pengisian Raport </h5>

                                <table id="quiz_student">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" data-width="20">Action</th>
                                            <th rowspan="2" data-field="name_student">Nama Peserta</th>
                                            <th colspan="4" data-align="center">Pertemuan</th>
                                            <th rowspan="2" data-width="30" data-field="value">Nilai</th>
                                            <th rowspan="2" data-width="30" data-field="grade">Grade</th>
                                            <th rowspan="2" data-width="30" data-field="note">Catatan</th>
                                        </tr>
                                        <tr>
                                            <th data-width="100">
                                                <input type="text" name="date_absen[]" class="form-control form-date-absen">
                                            </th>
                                            <th data-width="100">
                                                <input type="text" name="date_absen[]" class="form-control form-date-absen">
                                            </th>
                                            <th data-width="100">
                                                <input type="text" name="date_absen[]" class="form-control form-date-absen">
                                            </th>
                                            <th data-width="100">
                                                <input type="text" name="date_absen[]" class="form-control form-date-absen">
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <!-- Select 2 js -->
    <script type="text/javascript" src="{{asset('adminty/files/bower_components/select2/js/select2.full.min.js')}}"></script>
    <!-- Bootstrap date-time-picker js -->
    <script type="text/javascript" src="{{asset('adminty/files/bower_components/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('adminty/files/assets/pages/advance-elements/moment-with-locales.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('adminty/files/assets/pages/advance-elements/bootstrap-datetimepicker.min.js')}}"></script>
    <script>
        var state = {
            countFormStudent: 1,
            dataStudent: [],
            quiz_id: '',
            class_room_id: '',
        }

        var data = [];
        var form = $('#form');
        var btnSave = $('#btnSave');
        var nameClassRoom = $('#name_quiz');
        var firstStudent = $('#first_student');
        var multiStudent = $('.multi-student');
        var quizStudentTable = $('#quiz_student');

        var filterQuiz = $('#filter_quiz');
        var filterClass = $('#filter_class');

        $(document).ready(function() {
            readData();
            loadDataStudent();

            $('.select2').select2();
            // Using Locales
            $('.form-date-absen').datetimepicker({
                locale: 'id',
                format:'DD/MM',
                icons: {
                    time: "icofont icofont-clock-time",
                    date: "icofont icofont-ui-calendar",
                    up: "icofont icofont-rounded-up",
                    down: "icofont icofont-rounded-down",
                    next: "icofont icofont-rounded-right",
                    previous: "icofont icofont-rounded-left"
                }
            });
        });

        // function edit(index) {
        //     data = quizStudentTable.bootstrapTable('getData');
        //     form.attr('action', $('#edit_'+index).attr('data-link'));

        //     nameClassRoom.val(data[index].name_quiz);
        // }

        function remove(index) {
            data = quizStudentTable.bootstrapTable('getData');

            Swal.fire({
                title: 'Yakin hapus peserta <b>'+data[index].name_student+'</b> di kelas <b>'+data[index].name_class_room+'</b> dan <b>'+data[index].name_quiz+'</b> ?',
                showDenyButton: false,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: `Delete`,
                confirmButtonColor: 'red',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    location.href = `{{url('quiz-student/delete')}}/${data[index].id}`;
                    // Swal.fire('Data terhapus!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Data tidak dihapus', '', 'info');
                }
            });
        }

        function sendFilter() {
            state.quiz_id = filterQuiz.val();
            state.class_room_id = filterClass.val();

            quizStudentTable.bootstrapTable('refresh');
        }

        function readData() {
            quizStudentTable.bootstrapTable({
                url: "{{url('/quiz-student/ajaxRead')}}",
                method: 'get',
                locale: 'en-US',
                classes: 'table table-bordered table-hover',
                toolbar: '#toolbar',
                //showRefresh: true,
                search: true,
                pagination: true,
                sidePagination: 'server',
                pageSize: 10,
                sortable: true,
                queryParams: function(params) {
                    params.quiz_id = state.quiz_id;
                    params.class_room_id = state.class_room_id;

                    return params;
                },
                //idField: 'NRP',
                // columns: [
                //     {
                //         width: 10,
                //         title: 'Action',
                //         formatter: function (value, row, index) {
                //             var str = '';
                //             // str += `<a type="button" id="edit_${index}" data-link="{{url('quiz-student/update')}}/${row.id}" onclick="edit('${index}')" class="btn btn-info btn-xsm"><i class="fas fa-pencil-alt"></i></i></a> &nbsp;`;
                //             str += `<a type="button" id="remove_${index}" data-link="{{url('quiz-student/delete')}}/${row.id}" onclick="remove('${index}')" class="btn btn-danger btn-xsm"><i class="fas fa-trash"></i></a>`;

                //             return str;
                //         },
                //     },
                //     {
                //         field: 'name_student',
                //         title: 'Nama Peserta',
                //     },
                //     {
                //         title: 'Pertemuan',
                //         rowspan: 2,
                //     },
                // ]
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
                        var optionNewFormStudent = '';
                        state.dataStudent = result.data;

                        // optionNewFormStudent += '<option> Pilih Peserta </option>';
                        // $.each(state.dataStudent, function(index, item) {
                        //     optionNewFormStudent += '<option value="'+item.id+'"> '+item.name_student+' </option>';
                        // });

                        // firstStudent.prepend(optionNewFormStudent);
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

        function addFormStudent() {
            var newFormStudent = '';
            var optionNewFormStudent = '';
            var countFormStudent = state.countFormStudent + 1;
            state.countFormStudent = countFormStudent;

            optionNewFormStudent += '<option> Pilih Peserta </option>';
            $.each(state.dataStudent, function(index, item) {
                optionNewFormStudent += '<option value="'+item.id+'"> '+item.name_student+' </option>';
            });

            newFormStudent += '<div class="form-group row multi-student" id="form_student_'+countFormStudent+'">';
                newFormStudent +='<div class="col-sm-12 col-md-12">';
                    newFormStudent +='<label class="block"> Pilih Peserta </label>';
                    newFormStudent +='<select name="students[]" class="form-control form-control-inverse form-select-student form-student">';
                        newFormStudent += optionNewFormStudent;
                    newFormStudent +='</select>';
                    newFormStudent +='<button type="submit" class="btn btn-sm btn-danger btn-delete-student" onclick="removeFormStudent('+countFormStudent+')">';
                        newFormStudent += '<i class="fas fa-close"></i>';
                    newFormStudent +='</button>';
                newFormStudent +='</div>';
            newFormStudent +='</div>';

            multiStudent.after(newFormStudent);
        }

        function removeFormStudent(index) {
            var countFormStudent = state.countFormStudent - 1;
            state.countFormStudent = countFormStudent;

            $('#form_student_'+index).remove();
        }
    </script>
@endsection
